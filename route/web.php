<?php declare(strict_types=1);

use App\Controller\HomeController;
use App\Controller\PostsController;
use Jahir\framework\src\Http\Response;

return [
  ['GET', '/', [HomeController::class, 'index']],
  ['GET', '/posts/{id:\d+}', [PostsController::class, 'show']],
  ['GET', '/hello/{name:.+}', fn(string $name) => new Response('Hello '.$name)],
];
