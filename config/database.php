<?php

return [
    'default' => env('DB_CONNECTION','mysql'),

    'connections' => [
        'mysql' => [
            'driver'   => 'pdo_mysql',
            'host'     => env('DB_HOST', '127.0.0.1'),
            'port'     => env('DB_PORT', '3306'),
            'dbname' => env('DB_DATABASE', 'xplore'),
            'user' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8mb4',
            'collation'=> 'utf8mb4_unicode_ci',
        ],

        'sqlite' => [
            'driver'   => 'pdo_sqlite',
            'database' => BASE_PATH . '/database.sqlite',
        ],
    ],
];
