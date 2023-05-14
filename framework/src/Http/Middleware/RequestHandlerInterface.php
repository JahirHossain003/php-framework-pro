<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}