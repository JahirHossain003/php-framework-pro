<?php declare(strict_types=1);

namespace App\Controller;

use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        dd($this->container->get('twig'));
        $content = "<h1>Hello World</h1>";
        return new Response($content);
    }
}