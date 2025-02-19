<?php

namespace Xplore\Http;

class Response
{
    private string $content;
    private array $headers = [];

    public function __construct(private int $statusCode = HttpResponse::OK)
    {
        $this->setStatusCode();
    }

    private function setStatusCode(): void
    {
        http_response_code($this->statusCode);
    }

    public function send(): string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

}