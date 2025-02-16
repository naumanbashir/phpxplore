<?php

namespace Xplore\Routing;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Xplore\Exceptions\HttpException;
use Xplore\Exceptions\HttpRequestMethodException;
use Xplore\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public static array $routes;

    public function route($method, $path, $handler): void
    {
        static::$routes[] = [strtoupper($method), $path, $handler];

    }

    public function registerRoutes(RouteCollector $collector): void
    {
        foreach (static::$routes as $route) {
            $collector->addRoute(...$route);
        }
    }

    public function dispatch(Request $request): array
    {
        $routeInfo = $this->getRouteInfo($request);

        [$handler, $vars] = $routeInfo;
        [$controller, $method] = $handler;

        return [[new $controller, $method], $vars];
    }

    private function getRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function(RouteCollector $routesCollector) {
            $this->registerRoutes($routesCollector);
        });


        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

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