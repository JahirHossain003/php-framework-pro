<?php

$container = new \League\Container\Container();

$container->add(\Jahir\Framework\Routing\RouterInterface::class, \Jahir\Framework\Routing\Router::class);

$container->add(\Jahir\Framework\Http\Kernel::class)->addArgument(\Jahir\Framework\Routing\RouterInterface::class);

return $container;