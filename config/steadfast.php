<?php

return [

    'base_url' => env('STEADFAST_BASE_URL', 'https://portal.packzy.com/api/v1'),

    'api_key' => env('STEADFAST_API_KEY'),

    'secret_key' => env('STEADFAST_SECRET_KEY'),

    'timeout' => env('STEADFAST_TIMEOUT', 30),

    'webhook' => [
        'enabled' => env('STEADFAST_WEBHOOK_ENABLED', true),
        'path' => env('STEADFAST_WEBHOOK_PATH', 'steadfast/webhook'),
        'middleware' => ['api'],
    ],

];
