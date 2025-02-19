<?php

namespace Xplore\Exceptions;

use ErrorException;
use Throwable;

class ErrorHandler
{
    public static function register()
    {
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleException(Throwable $e)
    {
        dd($e);
    }
}