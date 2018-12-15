<?php

$hotels      = explode('|', getenv('HOTELS_NODES'));
$hotelsNodes = [];
foreach ($hotels as $hotel) {
    [$name, $port] = explode(':', $hotel);
    $hotelsNodes[$name] = compact('name', 'port');
}

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'hotelsNodes' => $hotelsNodes,
    'queue' => [
        'host' => getenv('QUEUE_HOST'),
        'port' => getenv('QUEUE_PORT'),
        'user' => getenv('QUEUE_USER'),
        'password' => getenv('QUEUE_PASS')
    ],
    'nginx' => [
        'host' => getenv('NGINX_HOST')
    ]
];