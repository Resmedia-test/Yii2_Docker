<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '/',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '/articles.rss' => 'article/rss',

        '/search' => 'page/search',
        ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],

        //'/articles/<year:\d{4}>/<month:\d{2}>/<day:\d{2}>/<id:\d+>' => 'article/view',
        '/articles/<year:\d{4}>/<month:\d{2}>/<day:\d{2}>/<url:[a-z0-9-_]+>' => 'article/view',
        '/articles/<year:\d{4}>/<month:\d{2}>/<day:\d{2}>' => 'article',
        '/articles/<year:\d{4}>/<month:\d{2}>' => 'article',
        '/articles/<year:\d{4}>' => 'article',
        '/articles' => 'article',

        '/users' => 'user',
        '/users/<id:\d+>' => 'user/view',
        '/user/activation' => 'user/activation',
        '/user/reset-password' => '/user/reset-password',
        '/user/activation-email' => '/user/activation-email',

        // '/contact' => 'page/index',

        '/account' => 'account/index',
        '/login' => 'account/login',
        '/logout' => 'account/logout',
        '/signup' => 'account/signup',
        '/recovery' => 'account/recovery',

        '/account/file-upload' => 'account/file-upload',
        '/account/image-upload' => 'account/image-upload',
        '/account/images-get' => 'account/images-get',
        '/account/files-get' => 'account/files-get',

        '/add_article'=>'/request-article/create',

        '/go' => 'page/go',

        ['class' => 'frontend\components\DbUrlRule'],
        [
            'pattern' => '/<view>',
            'route' => 'page/static',
            'suffix' => ''
        ],

    ],
];