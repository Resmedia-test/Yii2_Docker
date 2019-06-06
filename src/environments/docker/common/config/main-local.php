<?php
return [
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '674801029243442',
                    'clientSecret' => '9244508a27cd3610a3c96827c2574153',
                ],
                'vk' => [
                    'class' => 'common\components\clients\VKontakte',
                    'clientId' => '2435118',
                    'clientSecret' => 'BMfuCICNDKjAw6nPwNYV',
                ],
                'twitter' => [
                    'class' => 'common\components\clients\Twitter',
                    'consumerKey' => 'KxSJ1SuYrG0f2BVJ4xpJXpjxq',
                    'consumerSecret' => 'vN0hBW6lE5q4nC3r6hXzNltWWk0FVJYNLYFipZL4PcKTHH06Gv',
                    'via' => 'hav3y0ug0tmi1k',
                ],
                'odnoklassniki' => [
                    'class' => 'common\components\clients\Odnoklassniki',
                    'clientId' => '1153290496',
                    'clientPublic' => 'CBACNOLFEBABABABA',
                    'clientSecret' => '2ED8903AEB57E35162D6F09E',
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 3,
            'password' => 'toor',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=core',
            'username' => 'root',
            'password' => 'toor',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'test@test.ru',
                'password' => 'password',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
    ],
];
