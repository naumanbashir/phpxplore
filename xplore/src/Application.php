<?php

namespace Xplore;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Xplore\Exceptions\HttpException;
use Xplore\Http\HttpResponse;
use Xplore\Http\Request;
use Xplore\Http\Response;
use Xplore\Routing\RouterInterface;

class Application
{
    public static string $appEnv;

    public function __construct(
        private RouterInterface $router
    )
    {
        static::$appEnv = env('APP_ENV', 'dev');
    }

    public function withRouting(array|string|null $web = null): static
    {
        if (is_string($web)) {
            include_once $web;
        };

        if (is_array($web)) {
            foreach ($web as $routeFile) {
                include_once $routeFile;
            }
        };

        return $this;
    }

    public function handleRequest(Request $request): string
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);

            $response =  call_user_func_array($routeHandler, $vars);

        } catch (\Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

        return $response->send();
    }

    /**
     * @throws \Exception $exception
     */
    private function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array(static::$appEnv, ['dev', 'test']))
            throw $exception;

        if ($exception instanceof HttpException)
            return (new Response($exception->getCode()))->setContent($exception->getMessage());

        return (new Response(HttpResponse::INTERNAL_SERVER_ERROR))->setContent('Server Error');
    }
}