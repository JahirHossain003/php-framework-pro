<?php

namespace Jahir\Framework\Dbal\Event;

use Doctrine\DBAL\Connection;
use Jahir\Framework\EventDispatcher\EventDispatcher;

class DataMapper
{
    public function __construct(
        private Connection $connection,
        private EventDispatcher $eventDispatcher
    )
    {
    }

    public function save(Entity $entity): int|string|null
    {
        $this->eventDispatcher->dispatch(new PostPersistEvent($entity));

        return $this->connection->lastInsertId();
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }
}