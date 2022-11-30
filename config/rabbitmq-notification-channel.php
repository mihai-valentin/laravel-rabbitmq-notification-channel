<?php

declare(strict_types=1);

return [
    /*
     * default_queue - the default rabbitmq queue name used in the Publisher
     * */
    'default_queue' => env('RABBITMQ_NOTIFICATION_DEFAULT_QUEUE', 'laravel-notifications'),

    /*
     * rabbitmq - rabbitmq connection config
     * */
    'rabbitmq' => [
        'host' => env('RABBITMQ_NOTIFICATION_HOST', 'localhost'),
        'port' => env('RABBITMQ_NOTIFICATION_PORT', '5672'),
        'user' => env('RABBITMQ_NOTIFICATION_USER', 'guest'),
        'password' => env('RABBITMQ_NOTIFICATION_PASSWORD', 'guest'),
    ],
];
