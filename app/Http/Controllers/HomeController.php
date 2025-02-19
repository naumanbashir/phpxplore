<?php

namespace App\Http\Controllers;

use Xplore\Classes\Widget;
use Xplore\Http\Response;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('home.html.twig');
    }
}