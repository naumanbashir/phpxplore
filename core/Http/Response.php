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

    public function send(): void
    {
        echo $this->content;
    }
}