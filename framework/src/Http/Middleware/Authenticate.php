<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{
    private bool $isAuthenticated = true;

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->isAuthenticated) {
            throw new \Exception('Authentication failed', 401);
        }

        return $handler->handle($request, $handler);
    }
}