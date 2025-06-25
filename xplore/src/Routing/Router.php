<?php

namespace Xplore\Routing;

use Psr\Container\ContainerInterface;
use Xplore\Http\Contracts\RequestInterface;
use Xplore\Http\Response;

class Router implements RouterInterface
{
    private array $routes = [];
    private string $prefix = '';

    public function __construct(
        public ContainerInterface $container,
    )
    {
    }


    public function get(string $uri, callable|array $handler): void
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post(string $uri, callable|array $handler): void
    {
        $this->addRoute('POST', $uri, $handler);
    }

    public function put(string $uri, callable|array $handler): void
    {
        $this->addRoute('PUT', $uri, $handler);
    }

    public function delete(string $uri, callable|array $handler): void
    {
        $this->addRoute('DELETE', $uri, $handler);
    }

    /**
     * Add route to the router.
     */
    private function addRoute(string $method, string $uri, callable|array $handler): void
    {
        $uri = $this->prefix . '/' . trim($uri, '/');
        $this->routes[$method][$uri] = $handler;
    }

    /**
     * Process the incoming request and dispatch it.
     */
    public function dispatch(RequestInterface $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $params = [];

            if ($this->matchRoute($route, $uri, $params)) {

                // Execute Controller Method
                if (is_array($handler)) {
                    [$controllerClass, $controllerMethod] = $handler;
                    $controller = $this->container->get($controllerClass);

                    dd($this->container->call([$controller, $controllerMethod], $params));

                    return $this->container->call([$controller, $controllerMethod], $params);
                }

                // Execute Closure
                return call_user_func_array($handler, $params);
            }
        }

        return new Response(404, [], "404 Not Found");
    }

    /**
     * Match URI with route pattern and extract parameters.
     */
    private function matchRoute(string $route, string $uri, array &$params): bool
    {
        $pattern = preg_replace_callback('/\{(\w+):([^}]+)\}/', function ($matches) {
            return '(?P<' . $matches[1] . '>' . $matches[2] . ')';
        }, str_replace('/', '\/', $route));

        if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }
}