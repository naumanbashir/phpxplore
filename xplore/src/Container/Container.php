<?php

namespace Xplore\Container;

use Closure;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;

class Container implements ContainerInterface
{
    protected array $bindings = [];
    protected array $instances = [];
    protected array $aliases = [];
    protected array $contextual = [];
    protected array $resolvingCallbacks = [];
    protected array $resolvedCallbacks = [];
    protected array $resolving = [];
    protected array $buildStack = [];

    public function bind(string $abstract, string|callable|null $concrete = null, bool $singleton = false): BindingDefinition
    {
        $this->removeStaleInstance($abstract);

        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $definition = new BindingDefinition($concrete, $singleton);

        $this->bindings[$abstract] = $definition;

        return $definition;
    }

    public function singleton(string $abstract, string|callable|null $concrete = null): BindingDefinition
    {
        return $this->bind($abstract, $concrete, true);
    }

    public function instance(string $abstract, mixed $instance): void
    {
        $this->removeStaleInstance($abstract);
        $this->instances[$abstract] = $instance;
    }

    public function alias(string $abstract, string $alias): void
    {
        $this->aliases[$alias] = $abstract;
    }

    public function when(string $concrete): ContextualBindingBuilder
    {
        return new ContextualBindingBuilder($this, $concrete);
    }

    public function addContextualBinding(string $concrete, string $abstract, mixed $implementation): void
    {
        $this->contextual[$concrete][$abstract] = $implementation;
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]) || class_exists($id);
    }

    public function get(string $id): mixed
    {
        return $this->make($id);
    }

    public function make(string $abstract, array $parameters = []): mixed
    {
        $abstract = $this->getAlias($abstract);

        // Already resolved singleton
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->getConcrete($abstract);

        // Circular dependency check
        if (in_array($abstract, $this->buildStack)) {
            return $this->handleCircularReference($abstract);
        }

        $this->buildStack[] = $abstract;

        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete, $parameters);
        } else {
            $object = $this->make($concrete, $parameters);
        }

        array_pop($this->buildStack);

        $this->fireResolvingCallbacks($abstract, $object);

        if ($this->isSingleton($abstract)) {
            $this->instances[$abstract] = $object;
        }

        $this->fireResolvedCallbacks($abstract, $object);

        return $object;
    }

    /**
     * @throws \ReflectionException
     */
    protected function build(string|callable $concrete, array $parameters = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }

        $reflector = new ReflectionClass($concrete);


        if (!$reflector->isInstantiable()) {
            throw new RuntimeException("Class [$concrete] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (!$constructor) {
            return new $concrete;
        }

        $abstract = $concrete;
        $bindingArgs = [];

        if (isset($this->bindings[$concrete])) {
            $binding = $this->bindings[$concrete];
            $bindingArgs = $binding instanceof BindingDefinition
                ? $binding->getArguments()
                : [];
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->resolveDependencies($dependencies, array_merge($bindingArgs, $parameters), $concrete);

        return $reflector->newInstanceArgs($instances);
    }

    protected function resolveDependencies(array $parameters, array $overrides, string $context): array
    {
        $results = [];

        foreach ($parameters as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (array_key_exists($name, $overrides)) {
                $results[] = $overrides[$name];
                continue;
            }

            $dependency = $type->getName();

            if ($dependency === ContainerInterface::class) {
                $results[] = $this;
                continue;
            }

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $typeName = $type->getName();

                if (isset($this->contextual[$context][$typeName])) {
                    $results[] = $this->make($this->contextual[$context][$typeName]);
                } else {
                    $results[] = $this->make($typeName);
                }
            } elseif ($param->isDefaultValueAvailable()) {
                $results[] = $param->getDefaultValue();
            } else {
                throw new RuntimeException("Cannot resolve parameter [$name].");
            }
        }

        return $results;
    }

    /**
     * @throws \ReflectionException
     */
    public function call(callable $callback, array $parameters = []): mixed
    {
        if (is_array($callback)) {
            [$class, $method] = $callback;
            $reflector = new ReflectionMethod($class, $method);
        } else {
            $reflector = new ReflectionFunction($callback);
        }

        $args = $this->resolveDependencies($reflector->getParameters(), $parameters, get_class($class ?? null));

        return $callback(...$args);
    }

    protected function getConcrete(string $abstract): string|callable
    {
        if (!isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]->concrete;
    }

    protected function isSingleton(string $abstract): bool
    {
        return $this->bindings[$abstract]->singleton ?? false;
    }

    protected function isBuildable(string|callable $concrete, string $abstract): bool
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    protected function getAlias(string $abstract): string
    {
        return $this->aliases[$abstract] ?? $abstract;
    }

    protected function removeStaleInstance(string $abstract): void
    {
        unset($this->instances[$abstract]);
    }

    protected function handleCircularReference(string $abstract): mixed
    {
        // Gracefully return null or placeholder (optional)
        return null;
    }

    public function resolving(string $abstract, Closure $callback): void
    {
        $this->resolvingCallbacks[$abstract][] = $callback;
    }

    public function resolved(string $abstract, Closure $callback): void
    {
        $this->resolvedCallbacks[$abstract][] = $callback;
    }

    protected function fireResolvingCallbacks(string $abstract, mixed $object): void
    {
        foreach ($this->resolvingCallbacks[$abstract] ?? [] as $callback) {
            $callback($object, $this);
        }
    }

    protected function fireResolvedCallbacks(string $abstract, mixed $object): void
    {
        foreach ($this->resolvedCallbacks[$abstract] ?? [] as $callback) {
            $callback($object, $this);
        }
    }
}