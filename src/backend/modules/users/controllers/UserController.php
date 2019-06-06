<?php

namespace backend\modules\users\controllers;

use backend\components\ActiveController;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use common\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class UserController extends ActiveController
{
    public $layout = '//column2';
    public $modelClass = 'common\models\User';

    public function actions()
    {
        $actions = parent::actions();

        $actions['delete']['permanent'] = false;
        $actions['delete']['attribute'] = 'status';
        $actions['delete']['value'] = User::STATUS_DELETED;

        unset($actions['set']);

        return $actions;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'upload',  'update', 'set'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id=null)
    {
        $this->layout = false;

        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = 'json';

            if (Yii::$app->request->isAjax && isset($_POST['ajax']))
            {
                return \yii\widgets\ActiveForm::validate($model);
            }

            if(!empty($model->password))
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);

            if ($model->validate()) {
                $model->save();

                return true;
            }

            return $model->errors;
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }


    public function actionSet($id, $attr, $val)
    {
        /** @var ActiveRecord $model */
        $model = User::findOne($id);

        if(!isset($model))
            throw new NotFoundHttpException();

        if(isset($model->$attr))
        {
            $attrs = [$attr => $val];

            if($attr == 'news_main')
            {
                $modelClass = $this->modelClass;
                $modelClass::updateAll([$attr => 0]);
            }

            if ($attr == 'expert') {
                $attrs['time_article'] = time();
            }

            $model->updateAttributes($attrs);
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function afterLoadIndex(&$model)
    {
        if (empty($model->status)) {
            $model->status = [User::STATUS_EMAIL_NC, User::STATUS_INACTIVE, User::STATUS_ACTIVE, User::STATUS_DELETED];
        }
    }
}
