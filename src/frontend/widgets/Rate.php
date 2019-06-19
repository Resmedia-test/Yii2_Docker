<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class Rate extends Widget
{
    public $model_id = null;

    function run()
    {
        /*if (Yii::$app->user->isGuest) {
            return false;
        }*/

        if (empty($this->model_id)) {
            throw new \InvalidArgumentException('Требуется article id');
        }

        return $this->render('rate', ['model' => '', 'model_id' => $this->model_id]);
    }

}