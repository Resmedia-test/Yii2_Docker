<?php
return [
    'adminEmail' => 'support@pthb.ru',
    'senderEmail' => 'post@pthb.ru',
    'domainFrontend' => 'http://testSite.zu',
    'urlToCke' => empty(Yii::$app->request->hostInfo) ? '/scripts/config-cke.js?v6' : Yii::$app->request->hostInfo . '/scripts/config-cke.js?v6',
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
