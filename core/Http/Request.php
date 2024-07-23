<?php

namespace Panda\Http;

class Request
{
    public function getPath(): string
    {
        $query_string = $_SERVER['QUERY_STRING'];
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}