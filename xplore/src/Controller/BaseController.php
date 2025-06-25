<?php

namespace Xplore\Controller;

use Psr\Container\ContainerInterface;
use Xplore\Http\HttpResponse;
use Xplore\Http\Response;

abstract class BaseController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render($template, array $parameters = [], $response = null): Response
    {
        $content = $this->container->get('twig')->render($template, $parameters);

        $response ??= new Response(HttpResponse::OK, [],$content);
        $response->send();

        return $response;
    }
}