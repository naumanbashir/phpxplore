<?php

use \Xplore\Exceptions\ErrorHandler;

ErrorHandler::register();

$container = require_once APP_ROOT . '/bootstrap/services.php';

$app = $container->get(\Xplore\Application::class);

return $app->withRouting(web: __DIR__.'/../routes/web.php');