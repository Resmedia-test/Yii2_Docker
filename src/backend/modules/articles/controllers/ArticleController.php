<?php

namespace backend\modules\articles\controllers;

use common\models\Article;
use yii\bootstrap\Modal;
use yii\db\ActiveRecord;
use zxbodya\yii2\galleryManager\GalleryManagerAction;
use yii\web\NotFoundHttpException;


class ArticleController extends \backend\components\ActiveController
{
    public $modelClass = 'common\models\Article';
    public $modalSize = Modal::SIZE_LARGE;

    public function actions()
    {
        $actions = parent::actions();

        $actions['update']['afterLoad'] = [$this, 'afterLoad'];

        unset($actions['set']);

        $actions['galleryApi'] = [
            'class' => GalleryManagerAction::class,
            'types' => [
                'article' => Article::class
            ]
        ];

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'delete', 'update', 'set', 'upload', 'galleryApi'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function actionSet($id, $attr, $val)
    {
        /** @var ActiveRecord $model */
        $model = Article::findOne($id);

        if(!isset($model))
            throw new NotFoundHttpException();

        if(isset($model->$attr))
        {
            if($attr == 'article_main')
            {
                $modelClass = $this->modelClass;
                $modelClass::updateAll([$attr => 0]);
            }

            $model->updateAttributes([$attr => $val]);
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function afterLoad(&$model)
    {
        $model->detachBehavior('TimeStamp');

        if(empty($model->time_create))
            $model->time_create = time();

        if(!is_numeric($model->time_create))
            $model->time_create = strtotime($model->time_create);
    }

    public function beforeRender(&$model)
    {
        if(!empty($model->time_create))
            $model->time_create = date('d.m.Y H:i', $model->time_create);
        else
            $model->time_create = '';
    }
}