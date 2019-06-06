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
//                'google' => [
//                    'class' => 'common\components\clients\GoogleHybrid',
//                    'clientId' => '722562896574-398d4687sr2eo53lgv9m5tdpvsocrld5.apps.googleusercontent.com',
//                    'clientSecret' => 'A8Xv6w1lkAjeVAwCcFq7cdSg',
//                ],
//                'odnoklassniki' => [
//                    'class' => 'common\components\clients\Odnoklassniki',
//                    'clientId' => '1153290496',
//                    'clientPublic' => 'CBACNOLFEBABABABA',
//                    'clientSecret' => '2ED8903AEB57E35162D6F09E',
//                ],
            ],
        ],
        /*'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;port=8889;dbname=man_on;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock',
            'username' => 'man_on',
            'password' => 'yeMIuVpF',
            'charset' => 'utf8',
        ],*/
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://'./*developer:password@*/'localhost:27017/man_on',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
