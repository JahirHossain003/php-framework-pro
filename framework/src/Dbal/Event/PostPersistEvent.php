<?php

namespace Jahir\Framework\Dbal\Event;

use Jahir\Framework\EventDispatcher\Event;

class PostPersistEvent extends Event
{
    public function __construct(private Entity $subject)
    {
    }

    public function getSubject(): Entity
    {
        return $this->subject;
    }



}