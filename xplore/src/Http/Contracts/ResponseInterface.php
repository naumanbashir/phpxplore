<?php

namespace Xplore\Http\Contracts;

interface ResponseInterface
{
    public function getStatusCode(): int;

    public function getHeaders(): array;

    public function getBody(): string;

    public function withStatus(int $code): self;

    public function withHeader(string $name, string $value): self;

    public function withBody(string $content): self;

}