<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'hotel' => [
        'id' => getenv('HOTEL_ID'),
        'name' => getenv('HOTEL_NAME'),
        'city' => getenv('HOTEL_CITY'),
        'node' => getenv('HOTEL_NODE'),
    ]
];
