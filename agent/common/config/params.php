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
];