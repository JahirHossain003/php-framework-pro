<?php

namespace Jahir\Framework\Http\Event;

use Jahir\Framework\EventDispatcher\Event;
use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(private Response $response, private Request $request)
    {
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}