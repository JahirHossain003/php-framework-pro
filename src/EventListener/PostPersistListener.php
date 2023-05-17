<?php

namespace App\EventListener;

use Jahir\Framework\Dbal\Event\PostPersistEvent;

class PostPersistListener
{
    public function __invoke(PostPersistEvent $event)
    {
        // Do whatever you want
    }
}