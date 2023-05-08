<?php

use Jahir\Framework\Http\Kernel;
use Jahir\Framework\Routing\Router;
use Jahir\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;

$container = new Container();

$container->delegate(new ReflectionContainer(true));
$container->add('APP_ENV', new StringArgument("dev"));

$routes = include BASE_PATH.'/route/web.php';

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

return $container;