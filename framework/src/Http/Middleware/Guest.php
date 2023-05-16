<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\RedirectResponse;
use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;
use Jahir\Framework\Session\SessionInterface;

class Guest implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if ($this->session->has('auth_id')) {
            return new RedirectResponse('/dashboard');
        }

        return $handler->handle($request, $handler);
    }
}