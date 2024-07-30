<?php

namespace Panda\Http;


use FastRoute\RouteCollector;

class Router
{
    protected array $routes;

    public function get($path, $handler): void
    {
        $this->createRoute('GET', $path, $handler);
    }

    public function post($path, $handler): void
    {
        $this->createRoute('POST', $path, $handler);
    }

    private function createRoute($method, $path, $handler): void
    {
        $this->routes[] = [$method, $path, $handler];
    }

    public function registerRoutes(RouteCollector $collector): void
    {
        foreach ($this->routes as $route) {
            list($method, $path, $handler) = $route;
            $collector->addRoute($method, $path, $handler);
        }
    }

}