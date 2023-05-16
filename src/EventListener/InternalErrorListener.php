<?php

namespace App\EventListener;

use Jahir\Framework\Http\Event\ResponseEvent;

class InternalErrorListener
{
    public const ERROR_MIN_CODE = 499;

    public function __invoke(ResponseEvent $responseEvent)
    {
           if ($responseEvent->getResponse()->getStatus() > self::ERROR_MIN_CODE) {
               $responseEvent->stopPropagation();
           }
    }
}