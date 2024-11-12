<?php

namespace App\Http\Controllers;

use Xplore\Http\Response;

class PostsController extends Controller
{
    public function index(int $id): Response
    {
        return new Response('This is post: ' . $id);
    }
}