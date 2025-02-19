<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Xplore\Dbal\ConnectionFactory;
use Xplore\Routing\RouterInterface;

/** ---------------- Environment Variables --------------- */
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(BASE_PATH . '/.env');

require_once BASE_PATH . '/xplore/src/Support/helpers.php';

/** ---------------- Container Initialization --------------- */
$container = new League\Container\Container();

$container->delegate(new League\Container\ReflectionContainer(true));

$container->addShared(RouterInterface::class, \Xplore\Routing\Router::class);

$container->add(\Xplore\Application::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

/** ---------------------- Twig Templating Engine ---------------------- */
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

    $twig->addGlobal('APP_NAME', getenv('APP_NAME'));

    $twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
        return '/public/' . ltrim($path, '/');
    }));

    return $twig;
});

$container->inflector(\Xplore\Controller\BaseController::class)
    ->invokeMethod('setContainer', [$container]);

/** ---------------------- Database Abstraction Layer ---------------------- */
$container->addShared(ConnectionFactory::class)
    ->addArgument(new Configuration());

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

return $container;