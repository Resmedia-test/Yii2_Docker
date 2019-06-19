<?php

namespace backend\modules\comments;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\comments\controllers';
    public $defaultRoute = 'comment/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public function menu()
    {
        return [
            'Комментарии' => [
                '/comments',
                '/comments/index',
            ],
            'Жалобы' => [
                'comments/comment-abuse',
                'comments/comment-abuse/index',
            ],
        ];
    }
}
