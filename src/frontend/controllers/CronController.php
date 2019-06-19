<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\Setting;
use common\models\Subscription;
use common\models\SubscriptionComment;
use common\models\TaskArticle;
use frontend\components\Controller;
use Yii;
use yii\helpers\Url;

class CronController extends Controller
{
    public function actionArticlesPublish()
    {
        Article::updateAll(['time_publish' => 0, 'status' => 1], 'time_publish > 0 AND time_publish <= '.time());
    }

    public function actionCommentsNotifications($type_id)
    {
        //TODO: update users, add email
        $models = SubscriptionComment::find()
            ->innerJoinWith(['user'])
            ->where(['subscriptions_comments.type_id' => $type_id])
            ->andWhere(['<>', 'user.email', ''])
            ->all();

        if(!isset($models))
            Yii::$app->end();

        $messages = [];
        $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

        foreach($models as $model)
        {
            if($model->entity->comments !== $model->comments)
            {
                $email = Yii::$app->mailer->compose(['html' => 'notifyCommentsUser-html'], ['model' => $model]);
                $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
                $email->setSubject('Уведомление о новых комментариях');
                $email->setTo($model->user->email);

                $messages[] = $email;

                $model->updateAttributes(['comments' => $model->comments]);
            }
        }

        Yii::$app->mailer->sendMultiple($messages);
    }

    public function actionTaskArticle()
    {
        $time = time();

        $tasks = TaskArticle::find()->where(['<=', 'time', $time])->all();
        $subscriptions = Subscription::find()->where(['articles' => 1])->all();

        if (!isset($tasks) || !isset($subscriptions)) {
            Yii::$app->end();
        }

        $messages = [];

        foreach ($tasks as $task) {
            $models = Article::find()->where(['id' => explode(',', $task->models)])->all();
            $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

            $html = '';

            foreach($models as $model) {
                $html .= '<div style="margin-top: 5px;"></div>';
                $html .= '<h3><a href="'. Url::base(true) . $model->getUrl() .'">'.$model->name.'</a></h3>';
                $html .= '<p>'.$model->small_desc.'</p>';
                $html .= '<div style="color:#666; font-style:italic;">' .
                    Yii::$app->formatter->asDatetime($model->time_create, 'HH:mm, dd MMMM yyyy') .
                    ' &nbsp;&nbsp; <a href="'.Url::base(true) . $model->getUrl().'#comments">Комментарии: ' .
                    $model->comments.'</a></div>';
                $html .= '<div style="margin-top: 5px;"></div>';
            }

            foreach($subscriptions as $subscription) {
                if ($subscription->getEmail()) {
                    $email = Yii::$app->mailer->compose(['html' => 'taskArticle-html'],
                        [
                            'subscription' => $subscription,
                            'task' => $task,
                            'html' => $html
                        ]);
                    $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
                    $email->setSubject('Новые публикации');
                    $email->setTo($subscription->getEmail());
                    $email->getSwiftMessage()->getHeaders()->addTextHeader('List-Unsubscribe',
                        Url::to([
                            'subscription/unsubscribe',
                            'id' => $subscription->id,
                            'email' => $subscription->getEmail()
                        ], true));

                    $messages[] = $email;
                }
            }

            $task->delete();
        }

        Yii::$app->mailer->sendMultiple($messages);
    }
}