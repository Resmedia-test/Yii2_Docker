<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'backend',
    'name' => "Панель управления",
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/main/index',
    'homeUrl' => '/',
    'language' => 'ru',
    'bootstrap' => ['log'],
    'modules' => [
        'content' => [
            'class' => 'backend\modules\content\Module',
        ],
        'main' => [
            'class' => 'backend\modules\main\Module',
        ],
        'users' => [
            'class' => 'backend\modules\users\Module',
        ],
        'library' => [
            'class' => 'backend\modules\library\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
        'requests' => [
            'class' => 'backend\modules\requests\Module',
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
        ],
    ],
    'components' => [
        'assetManager' => [
            'linkAssets' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'main/main/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:j F, H:i',
            'timeFormat' => 'php:H:i:s',
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Etc/GMT-3',
            'locale' => 'ru-RU'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'request' => [
            'class' => 'common\components\Request',
            'baseUrl' => '/',
            'noCsrfRoutes' => [
                'main/main/upload'
            ]
        ],
        'urlManager' => require(__DIR__ . '/url-manager.php'),
        'urlManagerFront' => require(__DIR__ . '/../../frontend/config/url-manager.php'),
        'user' => [
            'class' => 'common\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['main/main/login'],
        ],
    ],
    'params' => $params,
];
