<?php

namespace App\Http\Controllers;

use Xplore\Http\Response;

class PostsController extends Controller
{
    public function index(int $id): Response
    {
        return $this->render('posts/index.html.twig', compact('id'));
    }

    public function create()
    {
        return $this->render('posts/create.html.twig');
    }
}