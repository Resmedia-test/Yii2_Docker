<?php

namespace backend\modules\articles;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\articles\controllers';
    public $defaultRoute = 'article/index';

    public function init()
    {
        parent::init();
    }

    public function menu()
    {
        return [
            'Публикации' => [
                '/articles',
                '/articles/index',
            ],
            'Рассылка' => [
                Yii::$app->urlManager->createUrl('articles/task'),
                Yii::$app->urlManager->createUrl('articles/task/index'),
            ],
            'Подписчики' => [
                Yii::$app->urlManager->createUrl('articles/subscription'),
                Yii::$app->urlManager->createUrl('articles/subscription/index'),
            ],
        ];
    }
}
