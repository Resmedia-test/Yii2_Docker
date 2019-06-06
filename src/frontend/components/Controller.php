<?php

namespace frontend\components;

use common\models\Section;
use Yii;
use yii\bootstrap\Modal;
use yii\web\ForbiddenHttpException;

class Controller extends \yii\web\Controller {
    const COOKIE_LANG = 'lang';
    const DEFAULT_LANG = 'ru';

    public $model = null;
    public $title = 'TestSite';
    public $image = '/img/logo.png';

    public $modalSize = Modal::SIZE_SMALL;

    public function beforeAction($action)
    {
        $this->checkPermissions();

        //setting section meta
        $section = Section::find()->where(['url' => substr($_SERVER['REQUEST_URI'], 1) ?: '/'])->one();

        if (isset($section)) {
            $this->model = $section;
        }


        return parent::beforeAction($action);
    }

    public function checkPermissions()
    {
        $id = Yii::$app->id . '.' . $this->id . '.' . $this->action->id;

        if( !Yii::$app->user->can($id, ['status' => Yii::$app->user->getStatus()], false) ) {
            throw new ForbiddenHttpException();
        }
    }
}