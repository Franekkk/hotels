<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$uuidPattern = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'   => '',
            'parsers'   => [
                'application/json' => \yii\web\JsonParser::class,
            ],
        ],
        'user'         => [
            'identityClass'   => \common\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules'               => [
                "<controller:\\w+>/<id:$uuidPattern>"               => '<controller>/view',
                "<controller:\\w+>/<action:\\w+>/<id:$uuidPattern>" => '<controller>/<action>',
                '<controller>/<action>'                             => '<controller>/<action>',
                '<controller>'                                      => '<controller>/index',
            ],
        ],
    ],
    'params'              => $params,
];

//[
//    'class'         => \yii\rest\UrlRule::class,
//    'controller'    => 'reservation',
//    'extraPatterns' => [
//        'GET status/<id>' => 'status',
//        'GET cancel/<id>' => 'cancel',
//    ],
//],