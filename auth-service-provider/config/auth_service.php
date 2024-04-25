<?php

return [
    'base_url' => env('AUTH_SERVICE_URL', 'http://auth.service/api'),
    'service_token' => env('AUTH_SERVICE_TOKEN', 'test'),
    'cache' => [
        'enabled' => env('AUTH_SERVICE_CACHE_ENABLED', true),
        'driver' => env('AUTH_SERVICE_CACHE_DRIVER'),
        'prefix' => env('AUTH_SERVICE_CACHE_PREFIX', 'auth:'),
        'ttl_token' => env('AUTH_SERVICE_CACHE_TTL_TOKEN', 1), // in minutes
        'ttl_ids' => env('AUTH_SERVICE_CACHE_TTL_IDS', 30), // in minutes
    ]
];
