<?php

use Doctrine\DBAL\Connection;
use Jahir\Framework\Console\Application;
use Jahir\Framework\Console\Command\MigrateDatabase;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Dbal\ConnectionFactory;
use Jahir\Framework\Http\Kernel;
use Jahir\Framework\Routing\Router;
use Jahir\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;

$basePath = dirname(__DIR__);

$dotEnv = new Dotenv();
$dotEnv->load($basePath.'/.env');

$container = new Container();
$container->delegate(new ReflectionContainer(true));
$container->add('basePath', new StringArgument($basePath));

$routes = include $basePath.'/route/web.php';
$templatePath = $basePath.'/templates';

$databaseUrl = 'sqlite:///' . $basePath . '/var/db.sqlite';

$container->add('APP_ENV', new StringArgument($_SERVER['APP_ENV']));

$container->add('base-command-namespace', new StringArgument('Jahir\\Framework\\Console\\Command\\'));

## Services
$container->add(RouterInterface::class, Router::class);

$container->add(
    \Jahir\Framework\Http\Middleware\RequestHandlerInterface::class,
    \Jahir\Framework\Http\Middleware\RequestHandler::class)
->addArgument($container);

$container->add(
    \Jahir\Framework\Http\Middleware\RouterDispatch::class
)->addArguments([
   RouterInterface::class,
   $container
]);

$container->addShared(\Jahir\Framework\EventDispatcher\EventDispatcher::class);

$container->add(Kernel::class)
    ->addArguments([
        $container,
        \Jahir\Framework\Http\Middleware\RequestHandlerInterface::class,
        \Jahir\Framework\EventDispatcher\EventDispatcher::class
        ]);

$container->add(Application::class)
    ->addArgument($container);

$container->add(\Jahir\Framework\Console\Kernel::class)
    ->addArguments([$container, Application::class]);

$container->addShared(
    \Jahir\Framework\Session\SessionInterface::class,
    \Jahir\Framework\Session\Session::class
);

$container->add('twig-template-factory', \Jahir\Framework\Template\TwigFactory::class)
    ->addArguments([\Jahir\Framework\Session\SessionInterface::class, new StringArgument($templatePath)]);

$container->addShared('twig', function () use ($container) {
   return $container->get('twig-template-factory')->create();
});

$container->add(AbstractController::class);

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);


$container->add(ConnectionFactory::class)
    ->addArguments([
        new StringArgument($databaseUrl)
    ]);

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add('database:migrations:migrate', MigrateDatabase::class)
    ->addArguments([Connection::class, new StringArgument($basePath.'/migration')]);

$container->add(\Jahir\Framework\Authentication\SessionAuthentication::class)
    ->addArguments([
        \App\Repository\UserRepository::class,
        \Jahir\Framework\Session\SessionInterface::class
    ]);

$container->add(\Jahir\Framework\Http\Middleware\RouteMiddleware::class)
    ->addArgument($routes);

return $container;