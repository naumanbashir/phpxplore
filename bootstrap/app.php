<?php

$container = require_once APP_ROOT . '/bootstrap/services.php';

return (new \Xplore\Application(APP_ROOT))
    ->withContainer($container)
    ->withRouting(
        web: __DIR__.'/../routes/web.php'
    );