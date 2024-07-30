<?php

namespace App\Http\Controllers;

use Panda\Http\Response;

class HomeController extends Controller
{
    public function index()
    {
        $content = '<h1>Working ...</h1>';
        return (new Response($content))->send();
    }
}