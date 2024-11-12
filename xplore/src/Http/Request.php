<?php

namespace Xplore\Http;

readonly class Request
{
    public function __construct(
        public array $getParams,
        public array $postParams,
        public array $cookies,
        public array $files,
        public array $server,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getPathInfo()
    {
        return $this->server['PATH_INFO'] ?? strtok($this->server['REQUEST_URI'], '?');
    }
}