<?php

declare(strict_types=1);

use Xplore\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$response = (require_once BASE_PATH . '/bootstrap/app.php')
    ->handleRequest(Request::createFromGlobals());

echo $response;