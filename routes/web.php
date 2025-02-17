<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Xplore\Routing\Router;

Router::route('get', '/', [HomeController::class, 'index']);
Router::route('get', '/posts/{id:\d+}', [PostsController::class, 'index']);