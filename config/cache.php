<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),

    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONN', 'cache'),
            'options' => [
                'prefix' => 'laravel_cache_',
                'database' => env('REDIS_CACHE_DB', 1),
            ],
        ],
        'database' => [
            'driver' => 'database',
            'connection' => null,
            'table' => 'cache',
        ],
    ],

    'prefix' => env('CACHE_PREFIX', 'laravel_cache_'),
];
