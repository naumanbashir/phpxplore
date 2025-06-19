<?php

use \Xplore\Container\Container;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\StringArgument;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Xplore\Application;
use Xplore\Dbal\ConnectionFactory;
use Xplore\Routing\Router;
use Xplore\Routing\RouterInterface;
use \Xplore\Console as Console;

/** ---------------- Environment Variables --------------- */
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(BASE_PATH . '/.env');

require_once BASE_PATH . '/xplore/src/Support/helpers.php';


/** ---------------- Container Initialization --------------- */

$container = new Container();
$container->singleton(RouterInterface::class, Router::class);
$container->bind(Application::class);

/** ---------------------- Twig Templating Engine ---------------------- */
$viewsPath = BASE_PATH . '/resources/views';
$cachePath = BASE_PATH . '/storage/cache/twig';

$container->singleton('filesystem-loader', FileSystemLoader::class)
    ->addArgument($viewsPath);

$container->singleton('twig', function () use ($container, $cachePath) {
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

$container->bind(\Xplore\Controller\BaseController::class, function ($controller) use ($container) {
    $controller->setContainer($container);
});

/** ---------------------- Database Abstraction Layer ---------------------- */
$container->bind(ConnectionFactory::class);

$container->singleton(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});


/** ---------------------- Console Command ---------------------- */
$container->bind(Console\Kernel::class)
    ->addArguments([$container, Console\Application::class]);

$container->bind(Console\Application::class)
    ->addArgument($container);

$container->bind(
    'database:migrations:migrate',
    Console\Command\MigrateDatabase::class
)->addArgument(Connection::class);

return $container;