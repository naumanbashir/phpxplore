<?php

if (!function_exists('config')) {
    function config($key, $default = null) {
        static $config = [];

        if (empty($config)) {
            $config = require BASE_PATH . '/config/database.php';
        }

        return $config[$key] ?? $default;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = $_ENV[$key];
        return $value !== false ? $value : $default;
    }
}
