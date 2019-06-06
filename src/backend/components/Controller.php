<?php

namespace backend\components;

use Yii;
use yii\bootstrap\Modal;
use yii\web\ForbiddenHttpException;

class Controller extends \yii\web\Controller
{
    public $modalSize = Modal::SIZE_DEFAULT;

    public function beforeAction($action)
    {
        $this->checkPermissions();

        return parent::beforeAction($action);
    }

    public function checkPermissions()
    {
        $id = Yii::$app->id . '.' . $this->module->id . '.' . $this->id . '.' . $this->action->id;

        if( !Yii::$app->user->can($id) )
        {
            if(Yii::$app->user->isGuest)
            {
                Yii::$app->user->loginRequired();
                return true;
            }

            throw new ForbiddenHttpException();
        }

        return true;
    }
}