<?php

namespace Panda\Exceptions;

use Throwable;

class HttpRequestMethodException extends HttpException
{
    public function __construct(
        string $message = "The request method is not allowed.",
        int $code = 400,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}