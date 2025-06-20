<?php

namespace Xplore\Container;

use Psr\Container\ContainerInterface;

class InflectorBuilder
{
    protected array $methods = [];

    public function __construct(protected string $type) {}

    public function invokeMethod(string $method, array $arguments = []): static
    {
        $this->methods[] = [$method, $arguments];
        return $this;
    }

    public function apply(object $instance, ContainerInterface $container): void
    {
        foreach ($this->methods as [$method, $args]) {
            $instance->$method(...$args);
        }
    }
}