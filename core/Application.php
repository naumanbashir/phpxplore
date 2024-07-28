<?php

namespace Panda;

use Panda\Http\Kernel;
use Panda\Http\Request;
use Panda\Http\Response;
use Panda\Http\Router;

class Application
{
    public static Application $app;
    public static string $ROOT_DIR;

    public Request $request;
    public Response $response;

    public Router $router;

    public function __construct($rootPath)
    {
        static::$app = $this;
        static::$ROOT_DIR = $rootPath;
    }

    public function withRouting(array|string|null $web = null): static
    {
        if (is_string($web)) {
            include_once $web;

        };

        if (is_array($web)) {
            foreach ($web as $routeFile) {
                include_once $routeFile;
            }
        };

        return $this;
    }

    public function handleRequest(Request $request): void
    {
        $this->request = $request;
        $this->router = new Router($this->request);
        echo $this->router->resolve();
    }
}