<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $model
 * @property integer $model_id
 * @property integer $user_id
 * @property integer $parent_id
 * @property integer $reply_id
 * @property string $text
 * @property string $ip
 * @property integer $likes
 * @property integer $time_create
 * @property integer $time_update
 * @property integer $status
 */
class Comment extends ActiveRecord
{
    const COMMENT_DELAY                 = 30; //30sec
    const COMMENT_UPDATE_DELAY          = 1800; //30min
    const SESSSION_LAST_COMMENT         = 'lastComment';
    const SETTING_EMAIL_NOTIFY_COMMENTS = 'moderatorEmail';
    const COOKIE_GUEST_VAR              = 'commentGuest';

    //RBAC
    const RBAC_FRONTEND_ALL     = 'frontend.comment';
    const RBAC_FRONTEND_CREATE  = 'frontend.comment.create';
    const RBAC_FRONTEND_UPDATE  = 'frontend.comment.update';
    const RBAC_FRONTEND_VIEW    = 'frontend.comment.view';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'model_id', 'user_id', 'text'], 'required'],
            [['model_id', 'user_id', 'parent_id', 'reply_id', 'likes', 'time_create', 'time_update', 'status'], 'integer'],
            [['text'], 'string'],
            [['model', 'ip'], 'string', 'max' => 255],

            [['text'], 'filter', 'filter'=>'\yii\helpers\HtmlPurifier::process'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'time_create',
                'updatedAtAttribute' => 'time_update',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'model' => 'Модель',
            'model_id' => '# Модели',
            'user_id' => 'Пользователь',
            'parent_id' => 'Parent ID',
            'reply_id' => 'Reply ID',
            'text' => 'Текст',
            'ip' => 'Ip',
            'likes' => 'Оценка',
            'time_create' => 'Опубликован',
            'time_update' => 'Обновлен',
            'status' => 'Удален',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getReply()
    {
        return $this->hasOne(self::class, ['id' => 'reply_id']);
    }

    public function getStringModel($model){
        switch ($model) {
            case 'Article':
                return Article::class;
                break;
            default:
                return Article::class;
        }
    }

    public function getUrl()
    {
        $modelClass = $this->getStringModel($this->model);
        $result = '';

        $model = new $modelClass();
        $model = $model->findOne($this->model_id);

        if(isset($model))
        {
            $result .= $model->getUrl();
            $result .= '#comment' . $this->id;
        }

        return $result;
    }
    /**
     * @return int
     */
    public function getItemName()
    {
        $modelClass = $this->getStringModel($this->model);
        $result = '';

        $model = new $modelClass();
        $model = $model->findOne($this->model_id);

        if(isset($model))
        {
            $result .= $model->getName();
        }

        return $result;
    }

    public function search($params)
    {
        $query = self::find();
        $this->load($params);

        if ($this->time_create && strpos($this->time_create, ' - ')) {
            $time = explode(' - ', $this->time_create);

            $query->andFilterWhere(['between', 'time_create', strtotime($time[0]), strtotime($time[1])]);
        }

        $query->with(['user']);

        //adjust the query by adding the filters
        $query->filterWhere(['model' => $this->model]);
        $query->andFilterWhere(['model_id' => $this->model_id]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['parent_id' => $this->parent_id]);
        $query->andFilterWhere(['reply_id' => $this->reply_id]);
        $query->andFilterWhere(['status' => $this->status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize', Yii::$app->cache->get(self::class . '_pageSize') ? Yii::$app->cache->get(self::class . '_pageSize') : ''),
            ]
        ]);

        return $dataProvider;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Yii::$app->session->set(self::SESSSION_LAST_COMMENT, time());

            $messages = [];

            //searhcing for subscriptions in users
            $subscriptions = SubscriptionComment::find()
                ->where(['model' => $this->model, 'model_id' => $this->model_id, 'type_id' => SubscriptionComment::TYPE_NOW])
                ->andWhere(['<>', 'user_id', Yii::$app->user->id])
                ->all();

            $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

            if (isset($subscriptions)) {
                foreach ($subscriptions as $model) {
                    $email = Yii::$app->mailer->compose(['html' => 'notifyCommentsUser-html'], ['subscription' => $model, 'model' => $this]);
                    $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
                    $email->setSubject('Уведомление о новом комментарии');
                    $email->setTo($model->user->email);

                    $messages[] = $email;
                }
            }

            //searching reply subscription
            if ($this->reply_id) {
                $subscription = SubscriptionComment::find()
                    ->where(['model' => $this->model, 'model_id' => $this->model_id, 'type_id' => SubscriptionComment::TYPE_REPLY])
                    ->andWhere(['user_id' => $this->reply->user_id])
                    ->one();

                if (isset($subscription)) {
                    $email = Yii::$app->mailer->compose(['html' => 'notifyCommentsReply-html'], ['subscription' => $subscription, 'model' => $this, 'user' => $this->reply->user]);
                    $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
                    $email->setSubject('Уведомление об ответе на комментарий');
                    $email->setTo($this->reply->user->email);

                    $messages[] = $email;
                }
            }

            //performing email for moderator
            $emailNotifyComments = Setting::find()->where(['code' => self::SETTING_EMAIL_NOTIFY_COMMENTS])->one();

            if(isset($emailNotifyComments))
            {
                $email = Yii::$app->mailer->compose(['html' => 'notifyCommentsModerator-html'], ['model' => $this]);
                $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
                $email->setSubject('Уведомление о новых комментариях');
                $email->setTo(explode(',', $emailNotifyComments->value));

                $messages[] = $email;
            }

            Yii::$app->mailer->sendMultiple($messages);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord && time() - self::COMMENT_DELAY < Yii::$app->session->get(self::SESSSION_LAST_COMMENT, 0))
        {
            $this->addError('text', 'Слишком часто отправляете комментарии, подождите 30 секунд!');

            return false;
        }

        return parent::beforeSave($this->isNewRecord);
    }

    public function getUserName()
    {
        $result = 'Пользователь удален.';

        $user = $this->user;
        if(isset($user))
            $result = $user->getFullName();

        return $result;
    }

    public function getUserCover()
    {
        $result = '/img/no-img.jpg';

        $user = $this->user;
        if(isset($user))
            $result = $user->getPreview();

        return $result;
    }
}
