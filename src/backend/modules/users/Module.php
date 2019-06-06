<?php

namespace backend\modules\users;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\users\controllers';
    public $defaultRoute = 'user/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    public function menu()
    {
        return [
            'ПОЛЬЗОВАТЕЛИ' => [
                '/users',
                '/users/index',
            ],
        ];
    }
}
