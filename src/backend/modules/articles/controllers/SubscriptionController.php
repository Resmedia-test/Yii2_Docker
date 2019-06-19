<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 15.07.16
 * Time: 21:51
 */

namespace backend\modules\articles\controllers;
use yii\bootstrap\Modal;

class SubscriptionController extends \backend\components\ActiveController
{
    public $modelClass = 'common\models\Subscription';
    public $modalSize = Modal::SIZE_DEFAULT;

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


}