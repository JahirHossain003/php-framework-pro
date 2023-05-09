<?php

namespace App\Controller;

use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\Response;

class PostsController extends AbstractController
{
    public function show(int $id): Response
    {
       return $this->render('post.html.twig', ['postId' => $id]);
    }
}