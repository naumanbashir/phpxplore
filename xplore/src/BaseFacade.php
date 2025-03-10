<?php

namespace Xplore;

abstract class BaseFacade
{
    protected static array $instances = [];

    /** ---------------- Get the registered class instance. ---------------- */
    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /** ---------------- Handle static method calls. ---------------- */
    public static function __callStatic($method, $args)
    {
        $instance = static::resolveInstance();
        return $instance->$method(...$args);
    }

    /** ---------------- Resolve the instance from the container. ---------------- */
    protected static function resolveInstance()
    {
        $accessor = static::getFacadeAccessor();

        global $container;

        if (!isset(self::$instances[$accessor])) {
            global $container;
            self::$instances[$accessor] = $container->get($accessor);
        }

        return self::$instances[$accessor];
    }
}