<?php
namespace App\Repository;

use App\Entity\Post;
use Jahir\Framework\Dbal\Event\DataMapper;

class PostDataMapper
{
    public function __construct(private DataMapper $dataMapper)
    {
    }

    public function save(Post $post): void {

        $stmt = $this->dataMapper->getConnection()->prepare(
            '
            INSERT INTO posts (title, body, created_at)
            VALUES(:title, :body, :createdAt)
            '
        );

        $stmt->bindValue(":title", $post->getTitle());
        $stmt->bindValue(":body", $post->getBody());
        $stmt->bindValue(":createdAt", $post->getCreatedAt()->format("Y-m-d H:i:s"));

        $stmt->executeStatement();

        $postId = $this->dataMapper->save($post);

        $post->setId($postId);
    }
}