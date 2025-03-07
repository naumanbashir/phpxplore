<?php

if (!function_exists('config')) {
    function config(string $config, $default = null) {
        [$configFile, $configurations ?? ''] = explode('.', $config);

        static $config = [];

        if (empty($config)) {
            $config = require BASE_PATH . '/config/' . $configFile . '.php';
        }

        return $config[$configurations] ?? $default;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = $_ENV[$key];
        return $value !== false ? $value : $default;
    }
}
