<?php declare(strict_types=1);

use Jahir\Framework\Http\Kernel;
use Jahir\Framework\Http\Request;
use Jahir\Framework\Routing\Router;

define('BASE_PATH', dirname(__DIR__));

require_once dirname(__DIR__).'/vendor/autoload.php';


$request = Request::createFromGlobals();

$router  = new Router();

$kernel = new Kernel($router);

$response = $kernel->handle($request);

$response->send();