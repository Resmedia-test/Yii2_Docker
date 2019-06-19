<?php

namespace frontend\controllers;

use common\components\helpers\StringHelper;
use common\models\Article;
use common\models\Setting;
use common\models\User;
use frontend\components\Controller;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{
    public function actionIndex(
        $year = null,
        $month = null,
        $day = null,
        $name = null,
        $create_from = null,
        $create_to = null,
        $userId = null
    ){
        if (!date($day . '.' . $month . '.' . $year)){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = Article::find()->where(['status' => 1]);

        $user = null;

        if (isset($userId )) {
            $user = User::findOne($userId );

            if (!isset($user)) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            $this->model = $user;

            $query->andWhere(['user_id' => $userId ]);
        }

        if (isset($year)) {
            $query->andWhere('YEAR(FROM_UNIXTIME(time_create)) = :year', [':year' => $year]);
        }

        if (isset($month)) {
            $query->andWhere('MONTH(FROM_UNIXTIME(time_create)) = :month', [':month' => $month]);
        }

        if (isset($day)) {
            $query->andWhere('DAY(FROM_UNIXTIME(time_create)) = :day', [':day' => $day]);
        }

        if (isset($create_from) && !empty($create_from)) {
            $query->andWhere('time_create >= :create_from', [':create_from' => strtotime($create_from)]);
        }

        if (isset($create_to) && !empty($create_to)) {
            $query->andWhere('time_create <= :create_to', [':create_to' => strtotime($create_to) + 86400]);
        }

        if (isset($name) && !empty($name)) {
            $query->andWhere('(name LIKE :name OR small_desc LIKE :name OR full_desc LIKE :name)', [':name' => '%' . $name . '%']);
        }

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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'user' => $user,
            'name' => $name,
            'create_from' => $create_from,
            'create_to' => $create_to,
        ]);
    }

    public function actionView($year, $month, $day, $url)
    {
        /** @var $model \common\models\Article */
        $model = Article::find()->where(['url' => $url, 'status' => 1])->one();

        $lastModUnix = isset($model->time_create) ? $model->time_create - 10800 : false;
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

        if (!date($day . '.' . $month . '.' . $year)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model == null || (isset($model) && !$model->compareDate($year, $month, $day))) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->model = $model;
        $model->views += 1;
        $model->time_update = time();
        $model->updateAttributes(['views', 'time_update']);

        if ($model->getBehavior('galleryBehavior')->getImages()) {
            $images = @$model->getBehavior('galleryBehavior')->getImages() ?: [];
            $image = reset($images);

            $this->image = isset($image) ? $image->getUrl('original') : '';
        } else {
            $this->image = '/img/default.png';
        }

        return $this->render('view', [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'model' => $model
        ]);
    }

    public function actionRss()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Article::find()->with(['user']),
            'pagination' => [
                'pageSize' => 15
            ],
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ]
            ],
        ]);

        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();

        $headers->set('Content-Type', 'application/rss+xml; charset=utf-8');
        $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

        echo \Zelenin\yii\extensions\Rss\RssView::widget([
            'dataProvider' => $dataProvider,
            'channel' => [
                'title' => function ($widget, \Zelenin\Feed $feed) {
                    $feed->addChannelTitle(Yii::$app->name);
                },
                'link' => Url::toRoute('/articles', true),
                'description' => 'Публикации сайта ' . $main_name->value ?: 'Название сайта',
                'language' => function ($widget, \Zelenin\Feed $feed) {
                    return Yii::$app->language;
                },
                'image' => function ($widget, \Zelenin\Feed $feed) {
                    $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
                    $feed->addChannelImage(Yii::$app->params['domainFrontend'] . '/img/default.png',
                        Url::toRoute('/', true),
                        100,
                        56,
                        $main_name->value ?: 'Название сайта');
                },
            ],
            'items' => [
                'title' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return $model->name;
                },
                'description' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return StringHelper::truncateWords($model->small_desc, 250);
                },
                'link' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return Url::toRoute([$model->getUrl()], true);
                },
                'author' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return $model->user->email . ' (' . $model->user->getFullName() . ')';
                },
                /*'guid' => function ($model, $widget, \Zelenin\Feed $feed) {
                    $date = new \DateTime();
                    $date->setTimestamp(@$model->time_update ?: $model->time_create);
                    return Url::toRoute([$model->getUrl()], true) . ' ' . $date->format(DATE_RSS);
                },*/
                'pubDate' => function ($model, $widget, \Zelenin\Feed $feed) {
                    $date = new \DateTime();
                    $date->setTimestamp($model->time_create);
                    return $date->format(DATE_RSS);
                },
                'source' => function ($model, $widget, \Zelenin\Feed $feed) {
                    $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
                    return $feed->addItemSource('Публикации сайта ' . $main_name->value ?: 'Название сайта', Url::toRoute('/articles.rss', true));
                },
            ]
        ]);
    }
}