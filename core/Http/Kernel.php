<?php

namespace Panda\Http;

use FastRoute\RouteCollector;
use Panda\Application;
use Panda\Routing\Router;
use function FastRoute\simpleDispatcher;

class Kernel
{
    private Router $router;

    public function __construct()
    {
        $this->router = Application::$app->router;
    }

    public function handle(Request $request): Response
    {
        try {

            [$routeHandler, $vars] = $this->router->dispatch($request);

            $response =  call_user_func_array($routeHandler, $vars);

        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 400);
        }

        return $response;
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