<?php

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Twig\Environment;
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
$cachePath = BASE_PATH . '/storage/cache/twig';

$container->addShared('filesystem-loader', FileSystemLoader::class)
    ->addArgument(new \League\Container\Argument\Literal\StringArgument($viewsPath));

$container->addShared('twig', function () use ($container, $cachePath) {
    $loader = $container->get('filesystem-loader');
    $twig = new Environment($loader, [
        'debug' => true,
        'cache' => $cachePath
    ]);

    $twig->addGlobal('APP_NAME', $_ENV['APP_NAME']);

    $twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
        return '/public/' . ltrim($path, '/');
    }));

    return $twig;
});

$container->inflector(\Xplore\Controller\BaseController::class)
    ->invokeMethod('setContainer', [$container]);



return $container;