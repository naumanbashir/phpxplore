<?php

namespace Panda\Http;

class Response
{
    public function __construct(
        private ?string $content,
        private int $statusCode = 200,
        private array $headers = []
    )
    {
    }

    public function send(): string
    {
        return $this->content;
    }

    public function setStatusCode(string $code): void
    {
        http_response_code($code);
    }
}