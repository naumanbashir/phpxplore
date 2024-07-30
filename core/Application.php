<?php

namespace Panda;

use FastRoute\RouteCollector;
use Panda\Http\Kernel;
use Panda\Http\Request;
use Panda\Http\Router;
use function FastRoute\simpleDispatcher;

class Application
{
    public static Application $app;

    public static string $ROOT_DIR;

    public Router $router;

    public function __construct($rootPath)
    {
        static::$app = $this;
        static::$ROOT_DIR = $rootPath;

        $this->router = new Router();
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

    public function handleRequest(Request $request)
    {
        echo (new Kernel())->handle($request);
    }
}