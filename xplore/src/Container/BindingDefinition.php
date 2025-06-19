<?php

namespace Xplore\Container;

use Closure;

class BindingDefinition
{
    public function __construct(
        public string|Closure $concrete,
        public bool $singleton = false,
        protected array $arguments = []
    ) {}

    public function addArgument(mixed $value): self
    {
        $this->arguments[] = $value;
        return $this;
    }

    public function addArguments(array $values): self
    {
        foreach ($values as $value) {
            $this->addArgument($value);
        }
        return $this;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}