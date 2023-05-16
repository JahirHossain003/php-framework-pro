<?php

namespace Jahir\Framework\Http\Middleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jahir\Framework\Http\Exception\HttpException;
use Jahir\Framework\Http\Exception\HttpMethodNotFoundException;
use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;
use function FastRoute\simpleDispatcher;

class RouteMiddleware implements MiddlewareInterface
{
    public function __construct(private array $routes)
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setHandler($routeInfo[1]);
                $request->setHandlerArgs($routeInfo[2]);

                if (is_array($routeInfo[1]) && isset($routeInfo[1][2])) {
                    $handler->injectMiddleware($routeInfo[1][2]);
                }
                break;
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

        return $handler->handle($request);
    }
}