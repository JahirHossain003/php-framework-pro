<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;
use Jahir\Framework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }
}