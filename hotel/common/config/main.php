<?php
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host=$db_host;dbname=$db_name",
            'username' => $db_user,
            'password' => $db_password,
            'charset' => 'utf8',
            'on afterOpen' => function($event) {
                // $event->sender refers to the DB connection
                $event->sender->createCommand("SET NAMES `utf8` COLLATE `utf8_polish_ci`")->execute();

            }
        ]
    ],
];
