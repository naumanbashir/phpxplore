<?php

namespace App\Http\Controllers;

use Panda\Http\Response;

class PostsController extends Controller
{
    public function index(int $id): Response
    {
        return new Response('This is post: ' . $id);
    }
}