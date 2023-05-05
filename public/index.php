<?php declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

// receive request
use Jahir\Framework\Http\Request;

$request = Request::createFromGlobals();

// perform some logic

// return response

echo 'Hello World';