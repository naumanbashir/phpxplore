<?php

namespace Xplore\Routing;

use FastRoute\Route;
use Psr\Container\ContainerInterface;
use Xplore\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container);

    public static function route(string $method, string $path, array $handler);
}