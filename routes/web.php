<?php

use App\Http\Controllers\HomeController;

$app = \Panda\Application::$app;

$app->router->get('/', [HomeController::class, 'index']);