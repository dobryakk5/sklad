<?php

/**
 * Фрагмент config/database.php — добавить в секцию 'connections'.
 *
 * Основное соединение Laravel ('mysql') — новая БД проекта.
 * Соединение 'bitrix' — read-only доступ к Битрикс БД.
 *
 * В .env добавить:
 *   BITRIX_DB_HOST=
 *   BITRIX_DB_PORT=3306
 *   BITRIX_DB_DATABASE=sitemanager
 *   BITRIX_DB_USERNAME=laravel_ro
 *   BITRIX_DB_PASSWORD=
 */

return [

    'connections' => [

        // --- основная БД Laravel (новый проект) ---
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => env('DB_DATABASE', 'alfasklad'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ],

        // --- Битрикс БД (read-only) ---
        'bitrix' => [
            'driver'    => 'mysql',
            'host'      => env('BITRIX_DB_HOST', '127.0.0.1'),
            'port'      => env('BITRIX_DB_PORT', '3306'),
            'database'  => env('BITRIX_DB_DATABASE', 'sitemanager'),
            'username'  => env('BITRIX_DB_USERNAME', 'laravel_ro'),
            'password'  => env('BITRIX_DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,       // Битрикс нередко хранит данные не по стандарту
            'engine'    => null,
            'options'   => [
                // Только чтение на уровне PDO — дополнительная страховка
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET SESSION TRANSACTION READ ONLY',
            ],
        ],
    ],
];
