<?php

namespace Xplore\Exceptions;

use ErrorException;
use Throwable;

class ErrorHandler
{
    public static function register()
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(Throwable $e)
    {
        error_log("Exception: {$e->getMessage()}");
        http_response_code(500);
        echo "<h1>Application Error</h1><p>{$e->getMessage()}</p>";
        exit;
    }

    public static function handleError($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            return;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");
            http_response_code(500);
            echo "<h1>Fatal Error</h1><p>Something went wrong.</p>";
        }
    }
}