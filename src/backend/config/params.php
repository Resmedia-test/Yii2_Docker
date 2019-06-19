<?php

return [
    'adminEmail' => 'support@testsite.docker',
    // INFO senderEmail - email that send all letters, must match with settings for sending emails шт main/mailer
    'senderEmail' => 'test@resmedia.ru',
    'domainFrontend' => 'http://testsite.docker',
    'domainBackend' => 'http://office.testsite.docker',
    'urlToCke' => empty(Yii::$app->request->hostInfo) ? '/scripts/config-cke.js?v7' : Yii::$app->request->hostInfo . '/scripts/config-cke.js?v7',
    'urlToSyncTranslit' => empty(Yii::$app->request->hostInfo) ? '/scripts/jquery.synctranslit.js?v8' : Yii::$app->request->hostInfo . '/scripts/jquery.synctranslit.js?v1',
    'kcfOptions' => [
        'uploadURL' => '@web/uploads/',
        'uploadDir'=> Yii::getAlias('@frontend/web/uploads/'),
        'disabled' => false,
        'denyZipDownload' => true,
        'denyUpdateCheck' => true,
        'denyExtensionRename' => true,
        'theme' => 'default',
        'access' => [  // @link http://kcfinder.sunhater.com/install#_access
            'files' => [
                'upload' => true,
                'delete' => true,
                'copy' => true,
                'move' => true,
                'rename' => true,
            ],
            'dirs' => [
                'create' => true,
                'delete' => true,
                'rename' => true,
            ],
        ],
        'types' => [  // @link http://kcfinder.sunhater.com/install#_types
            'files' => [
                'type' => '',
            ],
        ],
    ],
];
