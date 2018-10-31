<?php
return [
    'dealpaw' => [
        'driver' => 'mysql',
        'host' => env('DEALPAW_DB_HOST', '127.0.0.1'),
        'port' => env('DEALPAW_DB_PORT', '3306'),
        'database' => env('DEALPAW_DB_DATABASE', 'bestshop'),
        'username' => env('DEALPAW_DB_USERNAME', 'root'),
        'password' => env('DEALPAW_DB_PASSWORD', ''),
        'unix_socket' => env('DEALPAW_DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
];