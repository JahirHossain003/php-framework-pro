<?php

namespace Jahir\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jahir\Framework\Http\Exception\HttpException;
use Jahir\Framework\Http\Exception\HttpMethodNotFoundException;
use Jahir\Framework\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @throws HttpException
     * @throws HttpMethodNotFoundException
     */
    public function dispatch(Request $request): array
    {
        $routeInfo = $this->getRouteInfo($request);

        [$handler, $vars] = $routeInfo;

        [$controller, $method] = $handler;

        return [[new $controller, $method], $vars];
    }

    private function getRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = include BASE_PATH.'/route/web.php';
            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(", ", $routeInfo[1]);
                throw new HttpMethodNotFoundException("Only following methods are allowed: ".$allowedMethods);
            default:
                throw new HttpException("Method not found");
        }
    }
}