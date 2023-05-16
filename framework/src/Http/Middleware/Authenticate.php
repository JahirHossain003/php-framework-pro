<?php

namespace Jahir\Framework\Http\Middleware;

use Jahir\Framework\Http\RedirectResponse;
use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;
use Jahir\Framework\Session\Session;
use Jahir\Framework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if (!$this->session->has(Session::AUTH_KEY)) {
            $this->session->setFlash('error', 'Please log in');
            return new RedirectResponse('/login');
        }

        return $handler->handle($request, $handler);
    }
}