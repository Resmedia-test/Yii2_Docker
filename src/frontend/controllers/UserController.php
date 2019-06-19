<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\User;
use frontend\components\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionIndex()
    {
        $filterModel = new User();
        $filterModel->load(Yii::$app->request->post());

        $dataProvider = $filterModel->search();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel,
        ]);
    }

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

    public function actionActivation($token)
    {
        if (User::isActivationTokenValid($token)) {
            $model = User::findByActivationToken($token);

            if (isset($model)) {
                $cookie = isset($_COOKIE[Comment::COOKIE_GUEST_VAR]) ? $_COOKIE[Comment::COOKIE_GUEST_VAR] : null;
                $commentJson = @base64_decode($cookie);
                $commentData = @json_decode($commentJson);

                if (!empty($commentData)) {
                    $comment = new Comment();
                    $comment->user_id = $model->id;
                    $comment->model = $commentData->model;
                    $comment->model_id = $commentData->model_id;
                    $comment->reply_id = $commentData->reply_id;
                    $comment->text = $commentData->text;

                    if ($comment->validate())
                        $comment->save(false);
                }

                $model->activate();

                Yii::$app->user->login($model, 0);
                $model->checkCookieComment();

                Yii::$app->getSession()->setFlash('success', 'Поздравляем! Ваша учетная запись успешно активирована! Мы отправилли пароль Вам на почту.');
            }
        }

        $this->redirect('/');
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