<?php

namespace Jahir\Framework\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
   private iterable $listeners = [];
    public function dispatch(object $event)
    {
        foreach ($this->getListenersForEvent($event) as $listener) {
            $listener($event);
        }
    }

    public function addListener(string $eventName, callable $listener): self
    {
        $this->listeners[$eventName][] = $listener;
        return $this;
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event) : iterable
    {
        $eventName = get_class($event);

        if (array_key_exists($eventName, $this->listeners)) {
            return $this->listeners[$eventName];
        }

        return [];
    }
}