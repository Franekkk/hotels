<?php
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host=$db_host;dbname=test",
            'username' => $db_user,
            'password' => $db_password,
        ]
    ],
];
