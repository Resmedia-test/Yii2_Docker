<?php

namespace backend\modules\library;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\library\controllers';
    public $defaultRoute = 'book/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    public function menu()
    {
        return [
            'БИБЛИОТЕКА' => [
                '/library',
                '/library/index',
            ],
        ];
    }
}
