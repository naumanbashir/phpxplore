<?php

namespace Xplore\Routing;

use Psr\Container\ContainerInterface;
use Xplore\Http\Contracts\RequestInterface;
use Xplore\Http\Contracts\ResponseInterface;

interface RouterInterface
{
    public function get(string $uri, callable|array $handler): void;

    public function post(string $uri, callable|array $handler): void;

    public function put(string $uri, callable|array $handler): void;

    public function delete(string $uri, callable|array $handler): void;

    public function dispatch(RequestInterface $request): ResponseInterface;
}