<?php

namespace App\Repository;

use App\Entity\User;
use Jahir\Framework\Dbal\Event\DataMapper;

class UserDataMapper
{
    public function __construct(private DataMapper $dataMapper)
    {
    }

    public function save(User $user): void
    {

        $stmt = $this->dataMapper->getConnection()->prepare(
            '
            INSERT INTO users (username, password, created_at)
            VALUES(:username, :password, :createdAt)
            '
        );

        $stmt->bindValue(":username", $user->getUsername());
        $stmt->bindValue(":password", $user->getPassword());
        $stmt->bindValue(":createdAt", $user->getCreatedAt()->format("Y-m-d H:i:s"));

        $stmt->executeStatement();

        $userId = $this->dataMapper->save($user);

        $user->setId($userId);
    }
}