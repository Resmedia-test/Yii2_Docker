<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comments_abuses".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property integer $time_create
 */
class CommentAbuse extends ActiveRecord
{
    const SETTING_EMAIL_NOTIFY_ABUSE = 'moderatorEmail';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments_abuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id', 'time_create'], 'integer']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'time_create',
                'updatedAtAttribute' => false,
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
            'comment_id' => '# Комментария',
            'user_id' => '# Пользователя',
            'time_create' => 'Дата создания',
        ];
    }

    /**
     * @return int
     */
    
    public function getTimeCreate()
    {
        return $this->time_create;
    }

    public function getComment()
    {
        return $this->hasOne(Comment::class, ['id' => 'comment_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert)
        {
            //performing email for moderator
            $emailNotifyAbuse = Setting::find()->where(['code' => self::SETTING_EMAIL_NOTIFY_ABUSE])->one();
            $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

            if(isset($emailNotifyAbuse))
            {
                $email = Yii::$app->mailer->compose(['html' => 'notifyAbuseModerator-html'], ['model' => $this]);
                $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
                $email->setSubject('Уведомление о новой жалобе на комментарий');
                $email->setTo(explode(',', $emailNotifyAbuse->value));
                $email->send();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
