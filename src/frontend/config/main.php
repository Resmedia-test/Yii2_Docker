<?php

use yii\web\View;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'TestSite',
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'page/index',
    'homeUrl' => '/',
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'components' => [
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position' => View::POS_HEAD]
                ],
            ],
        ],
        'mobileDetect' => [
            'class' => 'ustmaestro\mobiledetect\MobileDetect'
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'test@resmedia.ru',
                'password' => 'GoodTest2019',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'page/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'metaTags' => [
            'class' => 'frontend\components\MetaTagsComponent',
            'generateCsrf' => false,
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'baseUrl' => '',
        ],
        'urlManager' => require(__DIR__ . '/url-manager.php'),
        'user' => [
            'class' => 'common\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
        'sitemap' => [
            'class' => 'himiklab\sitemap\Sitemap',
            'models' => [
                'common\models\Article',
                'common\models\Page',
                'common\models\User',
            ],
        ],
        'cacheExpire' => 0,
    ],
    'params' => $params,
];
