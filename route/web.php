<?php declare(strict_types=1);

return [
  ['GET', '/', [\App\Controller\HomeController::class, 'index']],
  ['GET', '/posts/{id:\d+}', [\App\Controller\PostsController::class, 'show']],
];
