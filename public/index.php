<?php declare(strict_types=1);

use Jahir\Framework\Http\Kernel;
use Jahir\Framework\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once dirname(__DIR__).'/vendor/autoload.php';


$request = Request::createFromGlobals();
$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();