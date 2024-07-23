<?php

namespace Panda;

use Panda\Http\Request;

class Application
{
    public static Application $app;
    public static string $ROOT_DIR;

    public Request $request;
    public Router $router;
    public Response $response;

    public function __construct($rootPath)
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request);
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }
}