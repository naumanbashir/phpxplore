<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Xplore\Routing\Router;

$container = \Xplore\Application::$container;
$router = $container->get(\Xplore\Routing\RouterInterface::class);

$router->route('get', '/', [HomeController::class, 'index']);
$router->route('get', '/posts/{id:\d+}', [PostsController::class, 'index']);