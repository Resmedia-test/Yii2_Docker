<?php

namespace backend\modules\comments\controllers;

class CommentController extends \backend\components\ActiveController
{
    public $modelClass = 'common\models\Comment';


    public function actions()
    {
        $actions = parent::actions();

        $actions['delete']['permanent'] = false;
        $actions['delete']['attribute'] = 'deleted';

        return $actions;
    }
}