<?php

namespace App\provider;


use App\EventListener\ContentLengthListener;
use App\EventListener\InternalErrorListener;
use App\EventListener\PostPersistListener;
use Jahir\Framework\Dbal\Event\PostPersistEvent;
use Jahir\Framework\EventDispatcher\EventDispatcher;
use Jahir\Framework\Http\Event\ResponseEvent;
use Jahir\Framework\ServiceProvider\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLengthListener::class
        ],
        PostPersistEvent::class => [
            PostPersistListener::class
        ]
    ];

    public function __construct(private EventDispatcher $dispatcher)
    {
    }

    public function register(): void
    {
        foreach ($this->listen as $eventName => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->dispatcher->addListener($eventName, new $listener);
            }
        }
    }
}