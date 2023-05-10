<?php

use Doctrine\DBAL\Connection;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Dbal\ConnectionFactory;
use Jahir\Framework\Http\Kernel;
use Jahir\Framework\Routing\Router;
use Jahir\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotEnv = new Dotenv();
$dotEnv->load(BASE_PATH.'/.env');

$templatePath = BASE_PATH.'/templates';

$databaseUrl = "sqlite:///".BASE_PATH.'/var/db.sqlite';

$container = new Container();

$container->delegate(new ReflectionContainer(true));
$container->add('APP_ENV', new StringArgument($_SERVER['APP_ENV']));
$container->add('base-command-namespace', new StringArgument('Jahir\\Framework\\Console\\Command\\'));

$routes = include BASE_PATH.'/route/web.php';

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('filesystem-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($templatePath));

$container->addShared('twig', Environment::class)
    ->addArgument('filesystem-loader');

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

$container->add(\Jahir\Framework\Console\Kernel::class)
    ->addArgument($container);

return $container;