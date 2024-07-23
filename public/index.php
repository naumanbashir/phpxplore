<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Panda\Application;

$rootDir = dirname(__DIR__);
$app = new Application($rootDir);

$app->run();


