<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 25.04.16
 * Time: 3:32
 */

namespace frontend\controllers;

use common\models\Auth;
use common\models\LoginForm;
use common\models\User;
use frontend\components\Controller;
use frontend\models\PasswordResetRequestForm;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AccountController extends Controller
{
    public $pass;

    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        //$this->layout = '//account';

        /**
         * @var $model User
         */
        $model = Yii::$app->user->getModel();
        $model->scenario = User::SCENARIO_UPDATE;

        $oldEmail = $model->email;

        if (isset($_POST['hasEditable'])) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post())) {

                if (!empty($model->birthday)) {
                    $model->birthday = strtotime($model->birthday);
                }

                if (!Yii::$app->security->validatePassword($model->passwordOld, $model->password_hash)) {
                    return ['output' => '', 'message' => 'Неправильный пароль'];
                }

                //$model->birthday = date('d.m.Y', $model->birthday);

                if ($model->validate()) {

                    if ($model->email !== $oldEmail) {
                        $model->emailNotConfirmed();
                        $model->sendEmailConfirmation();

                        Yii::$app->getSession()->setFlash('success', 'Пожалуйста, активируйте Ваш новый email адрес через ссылку в письме!');
                    }

                    $model->save(false);
                } else {
                    $errors = $model->errors;
                    return ['output' => '', 'message' => reset($errors)];
                }

            } else {
                return ['output' => '', 'message' => ''];
            }
        }

        $pass = Yii::$app->user->getModel();
        $pass->scenario = User::SCENARIO_UPDATE_PASSWORD;

        $pass->password = '';
        $pass->passwordOld = '';
        return $this->render('index', [
            'model' => $model,
            'pass' => $pass,
        ]);
    }

    public function actionLogin()
    {
        $this->layout = false;

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->login()) {

                Yii::$app->session->setFlash('success', 'C возвращением ' . $model->user->name . '!');
                return json_encode(array('redirect' => Url::to('/account/index')));
                //return false;//$this->redirect(['view', 'id' => $model->id]);
            } else {
                return $model->errors;
            }
        }

        return $this->renderAjax('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $this->layout = false;

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        $model->scenario = User::SCENARIO_SIGNUP;

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return \yii\widgets\ActiveForm::validate($model);
            }

            $model->setPassword($model->password);
            $model->generateActivationToken();
            $model->status = User::STATUS_EMAIL_NC;

            if ($model->validate()) {
                $transaction = $model->getDb()->beginTransaction();

                $model->save(false);
                $model->refresh();

                $authManager = Yii::$app->authManager;
                $userRole = $authManager->getRole(User::ROLE_USER);
                $authManager->assign($userRole, $model->id);

                $transaction->commit();

                Yii::$app->session->setFlash('success', 'Поздравляем! Регистрация прошла успешно! Дальнейшие инструкции высланы Вам на почту.');

                //Yii::$app->user->login($model);

                return json_encode(array('redirect' => Url::to('/')));
            } else {
                return $model->errors;
            }
        }

        return $this->renderAjax('signup', [
            'model' => $model,
        ]);
    }

    public function actionRecovery()
    {
        $this->layout = false;

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return \yii\widgets\ActiveForm::validate($model);
            }

            if ($model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->getSession()->setFlash('success', 'Проверьте свою электронную почту для получения дальнейших инструкций.');
                    return json_encode(array('redirect' => Url::to('/')));
                } else {
                    Yii::$app->getSession()->setFlash('error', 'К сожалению, мы не можем сбросить пароль по электронной почте.');
                    return json_encode(array('redirect' => Url::to('/')));
                }
            } else {
                return $model->errors;
            }
        }

        return $this->renderAjax('recovery', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        $this->layout = false;

        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'Всего доброго! Ждем Вас снова!');

        return $this->goHome();
    }

    public function onAuthSuccess($client)
    {
        Yii::$app->user->returnUrl = '/';
        Yii::$app->session->set(User::SESSION_CLIENT, $client->getId());

        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) {
                // login
                $user = $auth->user;
                Yii::$app->user->login($user, 3600 * 24 * 7);
            } else {
                // signup
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с таким email в {client} уже существует, но не связана с ним. Войти с помощью электронной почты первым, чтобы связать его.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    //$password = Yii::$app->security->generateRandomString(10);

                    $user = new User([
                        'name' => isset($attributes['first_name']) ? $attributes['first_name'] : (isset($attributes['name']) ? $attributes['name'] : ''),
                        'email' => isset($attributes['email']) ? $attributes['email'] : '',
                    ]);
                    $user->role = User::ROLE_USER;

                    //$user->setPassword($password);

                    $user->generateAuthKey();

                    $transaction = $user->getDb()->beginTransaction();
                    //$user->validate();
                    //var_dump($user->getErrors()); exit;
                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);

                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);

                $auth->save();
            }
        }
    }
}
