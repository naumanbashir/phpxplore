<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Xplore\Routing\Route;

//Route::get('/', [HomeController::class, 'index']);

Route::get('/', function () {
    dd('Working...');
});


Route::get('/posts/{id:\d+}', [PostsController::class, 'index']);
Route::get('/posts/add', [PostsController::class, 'create']);

