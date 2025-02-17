<?php

namespace Xplore\Http;

use Psr\Container\ContainerInterface;
use Xplore\Application;
use Xplore\Routing\Router;
use Xplore\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container,
    ) {}

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response =  call_user_func_array($routeHandler, $vars);

        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), $exception->getCode());
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
        include_once Application::$ROOT_DIR . '/resources/views/layouts/services.php';
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