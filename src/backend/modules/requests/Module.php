<?php

namespace backend\modules\requests;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\requests\controllers';
    public $defaultRoute = 'request-contact/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public function menu()
    {
        return [
            'Сообщения с сайта' => [
                Yii::$app->urlManager->createUrl('requests/request-contact'),
                Yii::$app->urlManager->createUrl('requests/request-contact/index'),
            ],
        ];
    }
}
