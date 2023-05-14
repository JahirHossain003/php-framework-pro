<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}