<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME');
        return $this->render('home.html.twig', compact('title'));
    }
}