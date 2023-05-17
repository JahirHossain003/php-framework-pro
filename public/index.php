<?php declare(strict_types=1);

use Jahir\Framework\Http\Kernel;
use Jahir\Framework\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

$container = require BASE_PATH.'/config/services.php';

/** @var \Jahir\Framework\EventDispatcher\EventDispatcher $eventDispatcher */
$eventDispatcher = $container->get(\Jahir\Framework\EventDispatcher\EventDispatcher::class);
$eventDispatcher->addListener(
    \Jahir\Framework\Http\Event\ResponseEvent::class,
    new \App\EventListener\InternalErrorListener()
)
    ->addListener(
  \Jahir\Framework\Http\Event\ResponseEvent::class,
  new \Jahir\Framework\EventListener\ContentLengthListener()
);

$eventDispatcher->addListener(
    \Jahir\Framework\Dbal\Event\PostPersistEvent::class,
    new \App\EventListener\PostPersistListener()
);

$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
