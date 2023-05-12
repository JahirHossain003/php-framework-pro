<?php

namespace Jahir\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\Exception\HttpException;
use Jahir\Framework\Http\Exception\HttpMethodNotFoundException;
use Jahir\Framework\Http\Request;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes = [];

    /**
     * @param Request $request
     * @param ContainerInterface $container
     * @throws HttpException
     * @throws HttpMethodNotFoundException
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $routeInfo = $this->getRouteInfo($request);

        [$handler, $vars] = $routeInfo;

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class)) {
              $controller->setRequest($request);
            }
            $handler = [$controller, $method];
        }

        return [$handler, $vars];
    }

    private function getRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(", ", $routeInfo[1]);
                $exception = new HttpMethodNotFoundException("Only following methods are allowed: ".$allowedMethods);
                $exception->setStatus(405);
                throw $exception;
            default:
                $exception = new HttpException("Not found");
                $exception->setStatus(404);
                throw $exception;
        }
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }
}