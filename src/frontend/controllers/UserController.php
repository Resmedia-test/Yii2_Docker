<?php

namespace frontend\controllers;

use common\models\User;
use frontend\components\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionView($id)
    {
        $model = User::findOne(['id' => $id, 'status' => 10]);

        if ($model == null)
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $this->model = $model;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function getUrl()
    {
        $result = '/user/' . $this->user_id;

        return $result;
    }

    public function actionActivationEmail($token)
    {
        if (User::isActivationTokenValid($token)) {
            $model = User::findByActivationToken($token);

            if (isset($model)) {
                $model->activateEmail();
                Yii::$app->user->login($model, 0);

                Yii::$app->getSession()->setFlash('success', 'Поздравляем! Ваш новый email адрес успешно активирован!');
            }
        }

        $this->redirect('/');
    }

    public function actionResetPassword($token)
    {
        if (User::isPasswordResetTokenValid($token)) {
            $model = User::findByPasswordResetToken($token);

            if (isset($model)) {
                $model->changePassword();

                Yii::$app->getSession()->setFlash('success', 'Мы отправили пароль на Вашу почту.');
            }
        }

        $this->redirect('/');
    }
}