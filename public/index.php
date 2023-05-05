<?php declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

// receive request
use Jahir\Framework\Http\Request;
use Jahir\Framework\Http\Response;

$request = Request::createFromGlobals();

// perform some logic

// return response

$content = '<h1>Hello World</h1>';

$response = new Response(content:$content, status:200, headers: []);

$response->send();