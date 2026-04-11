<?php

return [
    'host' => env('RABBITMQ_HOST', 'rabbitmq'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USER', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),
    'vhost' => env('RABBITMQ_VHOST', '/'),

    'exchanges' => [
        'analytics' => env('RABBITMQ_EXCHANGE_ANALYTICS', 'analytics'),
        'blog'      => env('RABBITMQ_EXCHANGE_BLOG', 'blog'),
    ],

    'queues' => [
        'frontend_blog' => env('RABBITMQ_QUEUE_FRONTEND_BLOG', 'frontend.blog'),
    ],
];
