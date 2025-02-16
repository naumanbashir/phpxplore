<?php

namespace Xplore;

use League\Container\Container;
use Xplore\Http\Kernel;
use Xplore\Http\Request;
use Xplore\Routing\Router;

class Application
{
    public static Application $app;

    public static string $ROOT_DIR;

    public static Container $container;

    public function __construct($rootPath)
    {
        static::$app = $this;
        static::$ROOT_DIR = $rootPath;
    }

    public function withContainer($container): Application
    {
        static::$container = $container;
        return static::$app;
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
        $kernel = static::$container->get(Kernel::class);
        $response = $kernel->handle($request);
        echo $response->send();
    }
}