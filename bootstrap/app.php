<?php

use Xplore\Application;
use \Xplore\Exceptions\ErrorHandler;

ErrorHandler::register();

$container = require_once BASE_PATH . '/bootstrap/services.php';

$app = $container->get(Application::class);

return $app->withRouting(web: BASE_PATH. '/routes/web.php');