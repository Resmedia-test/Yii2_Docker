<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '/',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '/account' => 'account/index',
        '/login' => 'account/login',
        '/logout' => 'account/logout',
        '/signup' => 'account/signup',
        '/recovery' => 'account/recovery',

        '/handbook/<id:\d+>' => 'book/view',
        '/handbook/<url:\w+>' => 'book/view',

        '/go' => 'page/go',
        '/search' => 'page/search',
        ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],

        ['class' => 'frontend\components\DbUrlRule'],

        [
            'pattern' => '/<view>',
            'route' => 'page/static',
            'suffix' => ''
        ],

    ],
];