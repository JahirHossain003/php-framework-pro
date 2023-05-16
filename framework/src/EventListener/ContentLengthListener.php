<?php

namespace Jahir\Framework\EventListener;

use Jahir\Framework\Http\Event\ResponseEvent;

class ContentLengthListener
{
    public function __invoke(ResponseEvent $responseEvent): void
    {
        $response = $responseEvent->getResponse();

        if (!array_key_exists('Content-Length', $response->getHeaders())) {
            $response->setHeader('Content-Length', strlen($response->getContent()));
        }
    }
}