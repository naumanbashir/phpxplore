<?php

namespace Xplore\Container;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ContextualBindingBuilder
{
    protected Container $container;
    protected string $concrete;

    public function __construct(Container $container, string $concrete)
    {
        $this->container = $container;
        $this->concrete = $concrete;
    }

    public function needs(string $abstract): self
    {
        $this->needs = $abstract;
        return $this;
    }

    public function give(string $implementation): void
    {
        $this->container->addContextualBinding($this->concrete, $this->needs, $implementation);
    }
}