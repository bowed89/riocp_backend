<?php

return [
    'host' => env('SOCKET_HOST', '127.0.0.1'),
    'port' => env('SOCKET_PORT', 6001),
    'scheme' => env('SOCKET_SCHEME', 'http'),

    'apps' => [
        'root' => [
            'key' => env('PUSHER_APP_KEY'), // 'root'
            'secret' => env('PUSHER_APP_SECRET'), // 'root'
            'id' => env('PUSHER_APP_ID'), // 'root'
            'enable_client_messages' => true, // Opcional, según tus necesidades
        ],
    ],

    'cors' => [
        'origin' => ['http://localhost:4200'], // Cambia esto según la URL de tu app Angular
        'methods' => ['GET', 'POST'],
        'allowedHeaders' => ['X-CSRF-Token', 'Authorization', 'Content-Type'],
        'exposedHeaders' => [],
        'maxAge' => 0,
        'supportsCredentials' => true,
    ],
];
