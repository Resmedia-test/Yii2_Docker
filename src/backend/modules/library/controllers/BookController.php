<?php

namespace backend\modules\library\controllers;

use common\models\Book;
use common\models\User;
use Yii;
use yii\bootstrap\Modal;
use zxbodya\yii2\imageAttachment\ImageAttachmentAction;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends \backend\components\ActiveController
{
    public $modelClass = 'common\models\Book';
    public $modalSize = Modal::SIZE_LARGE;

    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'delete', 'update', 'set'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function afterLoadUpdate(&$model)
    {
        if(empty($model->time_create))
            $model->time_create = time();

        if(!is_numeric($model->time_create))
            $model->time_create = strtotime($model->time_create);
    }

    public function beforeRender(&$model)
    {
        if(!empty($model->time_create))
            $model->time_create = date('d.m.Y H:i', $model->time_create);
    }
}
