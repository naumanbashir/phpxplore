<?php

use Xplore\Application;
use \Xplore\Exceptions\ErrorHandler;

define('BASE_PATH', dirname(__DIR__));

ErrorHandler::register();

$container = require_once BASE_PATH . '/bootstrap/services.php';

$app = $container->get(Application::class);

return $app->withRouting(web: BASE_PATH. '/routes/web.php');