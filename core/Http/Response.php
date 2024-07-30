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
        $this->setStatusCode();
    }

    public function send(): string
    {
        return $this->content;
    }

    private function setStatusCode(): void
    {
        http_response_code($this->statusCode);
    }
}