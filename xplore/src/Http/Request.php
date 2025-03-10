<?php

namespace Xplore\Http;

use Xplore\Http\Contracts\RequestInterface;

readonly class Request implements RequestInterface
{
    private string $method;
    private string $uri;
    private array $headers;
    private string $body;

    public function __construct(
        public array $queryParams,
        public array $postParams,
        public array $cookies,
        public array $files,
        public array $server,
    )
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = strtok($_SERVER['REQUEST_URI'], '?');
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPathInfo()
    {
        return $this->server['PATH_INFO'] ?? strtok($this->server['REQUEST_URI'], '?');
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getPostParams(): array
    {
        return $this->postParams;
    }
}