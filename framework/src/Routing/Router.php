<?php

namespace Jahir\Framework\Routing;

use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\Exception\HttpException;
use Jahir\Framework\Http\Exception\HttpMethodNotFoundException;
use Jahir\Framework\Http\Request;
use Psr\Container\ContainerInterface;

class Router implements RouterInterface
{
    /**
     * @param Request $request
     * @param ContainerInterface $container
     * @throws HttpException
     * @throws HttpMethodNotFoundException
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $routeHandler = $request->getHandler();
        $routeHandlerArgs = $request->getHandlerArgs();

        if (is_array($routeHandler)) {
            [$controllerId, $method] = $routeHandler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class)) {
              $controller->setRequest($request);
            }
            $routeHandler = [$controller, $method];
        }

        return [$routeHandler, $routeHandlerArgs];
    }
}