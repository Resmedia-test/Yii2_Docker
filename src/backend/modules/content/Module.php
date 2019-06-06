<?php

namespace backend\modules\content;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\content\controllers';
    public $defaultRoute = 'page/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public function menu()
    {
        return [
            'Страницы' => [
                '/content',
                '/content/page',
            ],
            'Меню' => [
                '/content/menu',
                '/content/menu/index',
            ],
        ];
    }
}
