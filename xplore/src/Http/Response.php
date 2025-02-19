<?php

namespace Xplore\Http;

class Response
{
    private string $content;
    public function __construct(
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

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    private function setStatusCode(): void
    {
        http_response_code($this->statusCode);
    }
}