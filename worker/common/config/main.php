<?php
$db_host      = getenv('DB_HOST');
$db_name_main = getenv('DB_NAME_MAIN');
$db_user      = getenv('DB_USER');
$db_password  = getenv('DB_PASSWORD');

$db = function ($db_name) use ($db_host, $db_user, $db_password) {
    return [
        'class'        => 'yii\db\Connection',
        'dsn'          => "mysql:host=$db_host;dbname=$db_name",
        'username'     => $db_user,
        'password'     => $db_password,
        'charset'      => 'utf8',
        'on afterOpen' => function ($event) {
            $event->sender->createCommand("SET NAMES `utf8` COLLATE `utf8_polish_ci`")->execute();
        },
    ];
};

$params    = require __DIR__ . '/params.php';
$hotelsDbs = [];
foreach ($params['hotelsNodes'] as $hotel) {
    $hotelsDbs["db_{$hotel['name']}"] = $db("hotel_{$hotel['name']}");
}

return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => array_merge([
        'cache'   => ['class' => 'yii\caching\FileCache'],
        'db'      => $db($db_name_main),
        'db_main' => $db($db_name_main),
        'mailer'  => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@common/mail',
            'useFileTransport' => true,
        ],
    ], $hotelsDbs),
];
