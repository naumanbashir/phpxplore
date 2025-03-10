<?php

if (!function_exists('config')) {
    function config(string $config, $default = null) {
        static $cachedConfig = [];

        $parts = explode('.', $config, 2);
        $configFile = $parts[0];
        $configKey = $parts[1] ?? null;

        if (!isset($cachedConfig[$configFile])) {
            $configPath = BASE_PATH . '/config/' . $configFile . '.php';
            if (file_exists($configPath)) {
                $cachedConfig[$configFile] = require $configPath;
            } else {
                return $default;
            }
        }

        if ($configKey === null) {
            return $cachedConfig[$configFile] ?? $default;
        }

        return $cachedConfig[$configFile][$configKey] ?? $default;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = $_ENV[$key];
        return $value !== false ? $value : $default;
    }
}
