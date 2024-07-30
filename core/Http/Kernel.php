<?php

namespace Panda\Http;

use Closure;
use FastRoute\RouteCollector;
use Panda\Application;
use function FastRoute\simpleDispatcher;

class Kernel
{
    public Router $router;

    public function __construct()
    {
        $this->router = Application::$app->router;
    }

    public function handle(Request $request): string
    {
        $dispatcher = simpleDispatcher(function(RouteCollector $routesCollector) {
            $this->router->registerRoutes($routesCollector);
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        [$status, [$controller, $method], $vars] = $routeInfo;

        return (new $controller())->$method();
    }

    public function renderView(string $view, $params = []): string
    {
        $layoutContent = $this->renderLayout();
        $viewContent = $this->renderOnlyView($view, $params);

        return str_replace('{{ content }}', $viewContent, $layoutContent);
    }

    private function renderLayout(): string
    {
        ob_start();
        include_once Application::$ROOT_DIR . '/resources/views/layouts/app.php';
        return ob_get_clean();
    }

    public function renderOnlyView($view, $params = []): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . '/resources/views/' . $view . '.php';
        return ob_get_clean();
    }
}