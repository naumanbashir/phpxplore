<?php

namespace Panda\Http;

class Router
{
    protected array $routes;

    public function __construct(
        public Request $request
    )
    {}

    public function get($path, $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function resolve()
    {
        return (new Kernel())->handle($this->routes, $this->request);
    }

}