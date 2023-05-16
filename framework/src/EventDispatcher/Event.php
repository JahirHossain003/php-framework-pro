<?php

namespace Jahir\Framework\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{
    private bool $propagationStop = false;
    public function isPropagationStopped(): bool
    {
        return $this->propagationStop;
    }

    public function shopPropagation(): void
    {
        $this->propagationStop = true;
    }
}