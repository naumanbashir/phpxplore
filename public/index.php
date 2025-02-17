<?php

declare(strict_types=1);

use Xplore\Http\Request;

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/vendor/autoload.php';

$response = (require_once APP_ROOT . '/bootstrap/app.php')
    ->handleRequest(Request::createFromGlobals());

echo $response;