<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 16.06.16
 * Time: 13:45
 */

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '/',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
    ],
];