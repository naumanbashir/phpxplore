<?php

namespace Panda;

use Closure;
use Panda\Http\Request;

class Router
{
    protected array $routes;

    public function __construct(
        public Request $request
    )
    {

    }

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
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler){
            Application::$app->response->setStatusCode(404);
            return 'No route found for "' . $path . '"';
        }

        /** Check if the Route handler is a Closure */
        if ($handler instanceof Closure)  return $handler();

        $controller = '';
        $method = '';

        /** Check if the Route handler is a string with Controller Name and method name imploded with @ */
        if (is_string($handler))
            list($controller, $method) = explode('@', $handler);

        /** Check if the Route handler is an array with Controller Name constant and method name */
        if (is_array($handler)) list($controller, $method) = $handler;

        if (!class_exists($controller))
            throw new \Exception("Controller '" . $controller . "' does not exist");

        $controller = new $controller();
        return $controller->{$method}();
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