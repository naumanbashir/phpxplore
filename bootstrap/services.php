<?php

$container = new League\Container\Container();

$appEnv = 'dev';

$container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));

$container->delegate(new League\Container\ReflectionContainer(true));

$container->add(\Xplore\Routing\RouterInterface::class, \Xplore\Routing\Router::class);

$container->add(\Xplore\Application::class)
    ->addArgument(\Xplore\Routing\RouterInterface::class)
    ->addArgument($container);

return $container;