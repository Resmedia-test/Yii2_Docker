<?php
/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 25.03.18
 * Time: 3:32
 */

namespace frontend\controllers;

use common\models\Article;
use common\models\Auth;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use common\models\Setting;
use common\models\Subscription;
use common\models\User;
use frontend\components\Controller;
use Imagine\Exception\InvalidArgumentException;
use Yii;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use zxbodya\yii2\imageAttachment\ImageAttachmentAction;

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
            'imgAttachApi' => [
                'class' => ImageAttachmentAction::class,
                'types' => [
                    'user' => User::class
                ]
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/uploads/articles/images',
                'path' => '@webroot/uploads/articles/images',
                'unique' => true,
                'validatorOptions' => [
                    'maxWidth' => 1000,
                    'maxHeight' => 1000
                ],
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => '/uploads/articles/images', // Directory URL address, where files are stored.
                'path' => '@webroot/uploads/articles/images', // Or absolute path to directory where files are stored.
                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']],

            ],
            'files-get' => [
                'class' => 'vova07\imperavi\actions\GetFilesAction',
                'url' => '/uploads/articles/files', // Directory URL address, where files are stored.
                'path' => '@webroot/uploads/articles/files', // Or absolute path to directory where files are stored.
            ],
            'file-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/uploads/articles/files', // Directory URL address, where files are stored.
                'path' => '@webroot/uploads/articles/files', // Or absolute path to directory where files are stored.
                'uploadOnlyImage' => false,
                'translit' => true,
                'validatorOptions' => [
                    'maxSize' => 30000
                ],
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
        $model->role = $model->getUserRole($model->id);

        $oldEmail = $model->email;

        if (isset($_POST['hasEditable'])) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post())) {

                if ($model->birthday) {
                    $model->birthday = strtotime($model->birthday);
                }

                if (!Yii::$app->security->validatePassword($model->passwordOld, $model->password_hash)) {
                    return ['output' => '', 'message' => 'Неправильный пароль'];
                }

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
                return ['output' => '', 'message' => reset($errors)];
            }
        }

        $pass = Yii::$app->user->getModel();
        $pass->scenario = User::SCENARIO_UPDATE_PASSWORD;

        $pass->password = '';
        $pass->passwordOld = '';

        $subscription = !empty($model->email) ? Subscription::findOne(['email' => $model->email]) : null;

        return $this->render('index', [
            'model' => $model,
            'pass' => $pass,
            'subscription' => @$subscription ?: new Subscription(),
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
                $model->getUser()->checkCookieComment();

                Yii::$app->session->setFlash('success', 'C возвращением ' . $model->user->getFullName() . '!');
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

    public function actionLoginComment()
    {
        $this->layout = false;

        $model = new User(['scenario' => User::SCENARIO_SIGNUP_FAST]);

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';

            $model->role = User::ROLE_USER;
            $model->password_hash = '';

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                return ActiveForm::validate($model);
            }

            if ($model->validate()) {
                $model->save(false);

                if (!empty($model->email)) {
                    $subscription = new Subscription();
                    $subscription->email = $model->email;
                    $subscription->name = $model->name;
                    $subscription->articles = Yii::$app->request->post('articles', 0) == 0 ? 1 : 1;
                    $subscription->save();
                }

                Yii::$app->session->setFlash('success', 'Поздравляем! Регистрация прошла успешно! Дальнейшие инструкции высланы вам на email.');
                return ['redirect' => Url::to('/')];
            }
        }

        return $this->renderAjax('loginComment', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $this->layout = false;

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        $model->scenario = User::SCENARIO_SIGNUP;

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
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

                if (!empty($model->email)) {
                    $subscription = new Subscription();
                    $subscription->email = $model->email;
                    $subscription->name = $model->name;
                    $subscription->articles = Yii::$app->request->post('articles', 0) == 0 ? 1 : 1;
                    $subscription->save();
                }

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

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
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

    public function actionArticles()
    {
        if (Yii::$app->user->getStatus() == User::STATUS_EMAIL_NC) {
            throw new ForbiddenHttpException;
        }

        $this->modalSize = Modal::SIZE_DEFAULT;

        $query = Article::find()->where(['<>', 'status', Article::STATUS_DELETED]);
        $query->andWhere(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 12,
            ]
        ]);

        return $this->render('indexArticle', [
            'dataProvider' => $dataProvider,
            //'searchModel' => $model,
        ]);
    }

    public function actionUpdateArticle($id = null)
    {
        if (Yii::$app->user->getStatus() == User::STATUS_EMAIL_NC) {
            throw new ForbiddenHttpException;
        }

        $this->layout = false;

        if (!$id) {
            $model = new Article();
        } else {
            $model = Article::findOne($id);
        }

        if (!isset($model)) {
            throw new NotFoundHttpException;
        }

        if (isset($model) && !$model->isNewRecord && $model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->isNewRecord) {
                $model->user_id = Yii::$app->user->id;
            }
            $model->status = Article::STATUS_MODERATION;

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                if ($model->isNewRecord) {
                    Yii::$app->user->refreshTimeArticle();
                }

                $email_article = Setting::findOne(['code' => 'email_requestArticle', 'status' => 1]);
                $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

                Yii::$app->mailer->compose(['html' => 'notifyArticleModerator-html'], ['model' => $model])
                    ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта'])
                    ->setTo(explode(',', @$email_article->value ?: ''))
                    ->setSubject('На сайте имеются статьи на проверку')
                    ->send();

                if ($model->isNewRecord) {
                    Yii::$app->session->setFlash('success', 'Статья успешно отправлена на модерацию!');
                } else {
                    Yii::$app->session->setFlash('success', 'После модерации статья станет снова доступна');
                }

                return json_encode(['status' => 'success']);
            }

            $errors = (array)$model->errors;
            $message = reset($errors);

            return json_encode(['status' => 'error', 'message' => $message]);
        }

        return $this->renderAjax('updateArticle', [
            'model' => $model,
        ]);
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

    public function actionSubscription($subscribe)
    {
        $model = Yii::$app->user->model;

        if (!empty($model->email)) {
            if ((int)$subscribe) {
                $subscription = new Subscription();
                $subscription->user_id = Yii::$app->user->id;
                $subscription->email = $model->email;
                $subscription->name = $model->fullName;
                $subscription->save();
            } else {
                Subscription::deleteAll(['email' => $model->email]);
            }
        }
    }

    public function actionSubscribe($attr, $val)
    {
        if (!Yii::$app->user->isGuest) {
            $model = Subscription::findOne(['email' => Yii::$app->user->model->email]);

            if (!isset($model)) {
                $model = new Subscription();
                $model->email = Yii::$app->user->model->email;
                $model->name = Yii::$app->user->model->name;
                $model->user_id = Yii::$app->user->id;
            }

            if ($model->hasAttribute($attr)) {
                $model->articles = $model->isNewRecord ? 0 : $model->articles;
                $model->$attr = (int)$val;
                $model->save();
            }
        }
    }

    public function actionRate($id, $rate)
    {
        $article = Article::findOne($id);

        if (!isset($article) || Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }

        if (!in_array($rate, range(1, 5))) {
            throw new InvalidArgumentException();
        }

        $article->addRate($rate);
        Yii::$app->user->setRate(Article::class, $id, $rate);
    }
}
