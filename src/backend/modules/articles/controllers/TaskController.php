<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 06.12.16
 * Time: 3:32
 */

namespace backend\modules\articles\controllers;


use backend\components\ActiveController;
use common\models\TaskArticle;
use Yii;

class TaskController extends ActiveController
{
    public $modelClass = 'common\models\TaskArticle';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['afterLoad'] = [$this, 'afterLoad'];

        unset($actions['set']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'delete', 'set'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function afterLoad()
    {
        $model = new TaskArticle();

        if ($model->load(Yii::$app->request->post())) {
            $model->time = strtotime($model->time);
            $model->models = implode(',', $model->models);

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            $model->save();
        }
    }
}