<?php

namespace Xplore\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Xplore\Exceptions\HttpException;
use Xplore\Exceptions\HttpRequestMethodException;
use Xplore\Http\Contracts\RequestInterface;
use Xplore\Http\Contracts\ResponseInterface;
use Xplore\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes = [];

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

    private function addRoute(string $method, string $uri, callable|array $handler): void
    {
        $this->routes[$method][$uri] = $handler;
    }

    public function dispatch(RequestInterface $request, ContainerInterface $container): ResponseInterface
    {
//        $method = $request->getMethod();
//        $uri = $request->getUri();
//        $handler = $this->routes[$method][$uri] ?? null;
//
//        if (!$handler) {
//            return new Response(404, [], "404 Not Found");
//        }
//
//        if (is_array($handler)) {
//            [$controllerId, $method] = $handler;
//            $controller = $container->get($controllerId);
//        }
//
//        return [[$controller, $method], $vars];


        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->routes[$method] as $pattern => $handler) {
            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches); // Remove the full match from the result

                if (is_array($handler)) {
                    [$controller, $method] = $handler;
                    $response = (new $controller())->$method(...$matches);
                } else {
                    $response = call_user_func_array($handler, $matches);
                }

                return new Response(200, ['Content-Type' => 'text/html'], $response);
            }
        }

        return new Response(404, [], "404 Not Found");
    }

    private function getRouteInfo(Request $request): array
    {
//        $dispatcher = simpleDispatcher(function(RouteCollector $routesCollector) {
//            $this->registerRoutes($routesCollector);
//        });
//
//
//        $routeInfo = $dispatcher->dispatch(
//            $request->getMethod(),
//            $request->getPathInfo()
//        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpRequestMethodException();
            default:
                throw new HttpException('The request route is not found', 404);
        }
    }
}