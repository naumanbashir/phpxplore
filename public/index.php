<?php

use Panda\Application;
use Panda\Http\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$rootDir = dirname(__DIR__);
$app = (new Application($rootDir))
        ->withRouting(
            web: __DIR__.'/../routes/web.php'
        );

$app->handleRequest(Request::createFromGlobals());


