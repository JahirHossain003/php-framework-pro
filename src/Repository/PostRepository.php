<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\DBAL\Connection;
use Jahir\Framework\Http\Exception\NotFoundException;

class PostRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function findOrFail(int $id): Post
    {
        $post = $this->fetchById($id);

        if (!$post) {
            throw new NotFoundException(sprintf('Post %d not found', $id));
        }

        return $post;
    }

    public function fetchById(int $id): ?Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select(['id', 'title', 'body', 'created_at'])
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id);

        $result = $queryBuilder->executeQuery();

        $row = $result->fetchAssociative();

        if (!$row) {
            return null;
        }

        $post = new Post(
            id: $row['id'],
            title: $row['title'],
            body: $row['body'],
            createdAt: new \DateTimeImmutable($row['created_at'])
        );

        return $post;
    }
}