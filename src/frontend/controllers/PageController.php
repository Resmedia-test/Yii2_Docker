<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\RequestContact;

use common\models\User;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use frontend\components\Controller;
use common\models\Page;
use yii\widgets\ActiveForm;

class PageController extends Controller
{
    //public $modalSize = Modal::SIZE_DEFAULT;
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\components\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = Page::findOne(['url' => '/', 'status' => Page::STATUS_PUBLISHED]);

        if ($model !== null)
            $this->model = $model;

        $model = new RequestContact();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            $model->status = 0;

            if ($model->save()) {
                $model->sendEmail();
                Yii::$app->session->setFlash('success', 'Спасибо! Мы свяжемся с Вами в ближайшее время');
                Yii::$app->session->set('requestContact', time());

                $this->redirect('/');

                return false;
            } else
                return $model->errors;
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('contactAjax', [
                'model' => $model,
            ]);
        }

        return $this->render('index', [
            'model' => $model]);
    }

    public function actionView($id)
    {
        $model = Page::findOne(['id' => $id, 'status' => Page::STATUS_PUBLISHED]);

        $lastModUnix = isset($model->time_update) ? $model->time_update - 10800 : false;
        $LastModified = date('D, d M Y H:i:s', $lastModUnix) . ' GMT';
        $IfModifiedSince = false;
        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
            $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
            $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        if ($IfModifiedSince && $IfModifiedSince >= $lastModUnix) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            exit;
        }
        header('Last-Modified: ' . $LastModified);

        if ($model == null)
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $this->model = $model;

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionStatic($view)
    {
        $this->layout = false;
        if ($view !== 'index' && preg_match('/[a-z][a-z0-9]{0,31}/', $view)) {
            try {
                return $this->render($view);
            } catch (\yii\base\InvalidParamException $e) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSearch($query = '')
    {
        $model = new DynamicModel(['query']);
        $model->addRule('query', 'required');
        $model->addRule('query', 'string');

        $data = [];

        if ($query) {
            $model->query = $query;

            $articles = Article::find()->where(['like', 'name', $model->query])
                ->orWhere(['like', 'small_desc', $model->query])
                ->orWhere(['like', 'full_desc', $model->query])
                ->all();
            foreach ($articles as $article)
                /** @var $article \common\models\Article */
                $data[] = [
                    'name' => $article->name,
                    'time_update' => Yii::$app->formatter->asDate($article->time_update, 'От dd.MM.YYYY'),
                    'description' => $article->small_desc,
                    'url' => $article->getUrl()
                ];

            $pages = Page::find()->where(['like', 'title', $model->query])
                ->orWhere(['like', 'content', $model->query])
                ->all();
            foreach ($pages as $page)
                /** @var $page \common\models\Page */
                $data[] = [
                    'name' => $page->title,
                    'time_update' => Yii::$app->formatter->asDate($page->time_update, 'От dd.MM.YYYY'),
                    'description' => $page->description,
                    'url' => $page->getLink()
                ];

            $users = User::find()->where(['like', 'name', $model->query])
                ->orWhere(['like', 'lastname', $model->query])
                ->orWhere(['like', 'about', $model->query])
                ->all();
            foreach ($users as $user)
                /** @var $user \common\models\User */
                $data[] = [
                    'name' => $user->name,
                    'time_update' => Yii::$app->formatter->asDate($user->last_login, 'От dd.MM.YYYY'),
                    'description' => $user->about,
                    'url' => $user->getUrl()
                ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('search', [
            'query' => $query,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionGo($url)
    {
        return $this->renderAjax('go', [
            'url' => $url,
        ]);
    }
}
