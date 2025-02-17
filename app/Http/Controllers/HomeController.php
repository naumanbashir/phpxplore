<?php

namespace App\Http\Controllers;

use Xplore\Classes\Widget;
use Xplore\Http\Response;

class HomeController extends Controller
{
    public function __construct(private Widget $widget)
    {
    }

    public function index()
    {
        $content = "<h1>Hello {$this->widget->name}</h1>";
        return new Response($content);
    }
}