<?php

/**
 * Фрагмент config/cache.php — добавить в секцию 'stores'.
 *
 * В .env добавить:
 *   REDIS_HOST=127.0.0.1
 *   REDIS_PORT=6379
 *   REDIS_PASSWORD=null
 *   CACHE_STORE=redis
 *
 * Используем отдельный database=1 чтобы:
 *   - не смешивать с Laravel session/queue (database=0)
 *   - можно сбрасывать отдельно: redis-cli -n 1 FLUSHDB
 */

return [

    'default' => env('CACHE_STORE', 'redis'),

    'stores' => [

        'redis' => [
            'driver'     => 'redis',
            'connection' => 'cache',   // секция из config/database.php redis.connections
            'lock_connection' => 'default',
        ],

    ],

    // Префикс изолирует ключи если несколько приложений на одном Redis
    'prefix' => env('CACHE_PREFIX', 'alfasklad_cache'),
];
