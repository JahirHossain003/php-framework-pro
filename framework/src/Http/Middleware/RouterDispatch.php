<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;
use Jahir\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{

    public function __construct(private RouterInterface $router, private ContainerInterface $container)
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {

        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
        $response = call_user_func_array($routeHandler, $vars);
        return $response;
    }
}