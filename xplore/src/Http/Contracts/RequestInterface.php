<?php

namespace Xplore\Http\Contracts;

interface RequestInterface
{
    public function getMethod(): string;

    public function getUri(): string;

    public function getHeaders(): array;

    public function getBody(): string;

    public function getQueryParams(): array;

    public function getPostParams(): array;
}