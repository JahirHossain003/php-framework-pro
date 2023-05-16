<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        RouteMiddleware::class,
        StartSession::class,
        RouterDispatch::class
    ];

    public function __construct(private ContainerInterface $container)
    {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            throw new \Exception('Something went wrong. No Middleware found !!', 500);
        }

        $middlewareClass = array_shift($this->middleware);
        $middleware = $this->container->get($middlewareClass);
        return $middleware->process($request, $this);
    }

    public function injectMiddleware(array $middlewares) {
        array_splice($this->middleware, 0,0,$middlewares);
    }
}