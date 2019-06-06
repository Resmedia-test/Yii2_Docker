<?php
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
            //'viewPath' => '@backend/mail',
            'useFileTransport' => false,//set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'manoninforus@gmail.com',
                'password' => 'Res&983&rt64ERsd',
                'port' => '587',
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
        'sitemap' => [
            'class' => 'himiklab\sitemap\Sitemap',
            'models' => [
                'common\models\Book',
                'common\models\Page',
            ],
        ],
        'cacheExpire' => 1,
    ],
    'params' => $params,
];
