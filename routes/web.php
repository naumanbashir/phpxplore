<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;

$app = \Panda\Application::$app;

$app->router->route('get', '/', [HomeController::class, 'index']);
$app->router->route('get', '/posts/{id:\d+}', [PostsController::class, 'index']);