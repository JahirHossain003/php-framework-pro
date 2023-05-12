<?php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\DBAL\Connection;

class PostDataMapper
{
    public function __construct(private Connection $connection)
    {
    }

    public function save(Post $post): void {

        $stmt = $this->connection->prepare(
            '
            INSERT INTO posts (title, body, created_at)
            VALUES(:title, :body, :createdAt)
            '
        );

        $stmt->bindValue(":title", $post->getTitle());
        $stmt->bindValue(":body", $post->getBody());
        $stmt->bindValue(":createdAt", $post->getCreatedAt()->format("Y-m-d H:i:s"));

        $stmt->executeStatement();

        $postId = $this->connection->lastInsertId();

        $post->setId($postId);
    }
}