<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        Authenticate::class,
        Success::class
    ];

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            throw new \Exception('Something went wrong. No Middleware found !!', 500);
        }

        $middlewareClass = array_shift($this->middleware);

        return (new $middlewareClass)->process($request, $this);
    }
}