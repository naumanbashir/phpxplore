<?php

$container = new League\Container\Container();

$container->add(\Xplore\Routing\RouterInterface::class, \Xplore\Routing\Router::class);

$container->add(\Xplore\Http\Kernel::class)
    ->addArgument(\Xplore\Routing\RouterInterface::class);

return $container;