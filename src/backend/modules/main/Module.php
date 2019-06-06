<?php

namespace backend\modules\main;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\main\controllers';
    public $defaultRoute = 'setting/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function menu()
    {
        return [
            'Настройки' => [
                '/main/setting',
                '/main/setting/index',
            ],
            'Разделы' => [
                '/main/section',
                '/main/section/index',
            ],
        ];
    }
}
