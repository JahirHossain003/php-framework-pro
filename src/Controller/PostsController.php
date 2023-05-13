<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostDataMapper;
use App\Repository\PostRepository;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\RedirectResponse;
use Jahir\Framework\Http\Response;
use Jahir\Framework\Session\SessionInterface;

class PostsController extends AbstractController
{
    public function __construct(
        private PostDataMapper $postDataMapper,
        private PostRepository $postRepository,
        private SessionInterface $session
    )
    {
    }

    public function show(int $id): Response
    {
        $post = $this->postRepository->findOrFail($id);

        return $this->render('post.html.twig', ['post' => $post]);
    }

    public function create(): Response
    {
        return $this->render('create-post.html.twig');
    }

    public function store(): Response
    {
        $title = $this->request->postParams['title'];
        $body = $this->request->postParams['body'];

        $post = Post::create($title, $body);

        $this->postDataMapper->save($post);

        $this->session->setFlash('success', sprintf('Post "%s" successfully created.', $title));

        return new RedirectResponse('/posts');
    }
}