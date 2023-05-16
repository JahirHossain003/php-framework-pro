<?php declare(strict_types=1);

use App\Controller\DashboardController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\PostsController;
use App\Controller\RegistrationController;

return [
  ['GET', '/', [HomeController::class, 'index']],
  ['GET', '/posts/{id:\d+}', [PostsController::class, 'show']],
  ['GET', '/posts', [PostsController::class, 'create']],
  ['POST', '/posts', [PostsController::class, 'store']],
  ['GET', '/register', [RegistrationController::class, 'index']],
  ['POST', '/register', [RegistrationController::class, 'register']],
  ['GET', '/login', [LoginController::class, 'index']],
  ['POST', '/login', [LoginController::class, 'login']],
  ['GET', '/dashboard', [DashboardController::class, 'index', [
      \Jahir\Framework\Http\Middleware\Authenticate::class
  ]]],
];
