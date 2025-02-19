<?php

use Twig\Loader\FilesystemLoader;
use Xplore\Routing\RouterInterface;

/** ---------------- Container Initialization --------------- */
$container = new League\Container\Container();

$container->delegate(new League\Container\ReflectionContainer(true));

$container->addShared(RouterInterface::class, \Xplore\Routing\Router::class);

$container->add(\Xplore\Application::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

/** ---------------------- Add Templating in Container ---------------------- */
$viewsPath = BASE_PATH . '/resources/views';

$container->addShared('filesystem-loader', FileSystemLoader::class)
    ->addArgument(new \League\Container\Argument\Literal\StringArgument($viewsPath));

$container->addShared('twig', \Twig\Environment::class)
    ->addArgument('filesystem-loader');

$container->inflector(\Xplore\Controller\BaseController::class)
    ->invokeMethod('setContainer', [$container]);

return $container;