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
                '/office/main/setting',
                '/office/main/setting/index',
            ],
            'Разделы' => [
                '/office/main/section',
                '/office/main/section/index',
            ],
        ];
    }
}
