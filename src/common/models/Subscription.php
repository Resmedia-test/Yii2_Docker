<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "subscriptions_comments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property integer $news
 * @property integer $life
 * @property integer $articles
 * @property integer $direct
 */
class Subscription extends ActiveRecord
{
    const SCENARIO_SUBSCRIBE = 'subscription';

    const SAVE_DELAY                    = 3600; //1h
    const SESSSION_LAST_SUBSCRIPTION    = 'lastSubscription';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscription';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return array_merge(
            $scenarios,
            [
                self::SCENARIO_SUBSCRIBE => ['name', 'email']
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'articles'], 'integer'],
            [['email'], 'email'],
            [['name'], 'string'],
            [['user_id'], 'required', 'on' => 'user'],
            [['email'], 'required', 'on' => self::SCENARIO_SUBSCRIBE],
            [['email', 'name'], 'string', 'max' => 255],
            ['email', 'unique', 'message' => 'Вы уже подписаны!'],
            [['email', 'name'], 'required'],

        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'email' => 'E-mail',
            'name' => 'Имя',
            'date' => 'Дата',
            'articles' => 'Статьи',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getEmail()
    {
        return !empty($this->user_id) ? ($this->user->email ?: null) : $this->email;
    }

    public function getName()
    {
        return !empty($this->user_id) ? ($this->user->name ?: null) : $this->name;
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->date = (new \DateTime())->format('Y-m-d H:i:s');
        }

        //check for subscribe account action
        if (Yii::$app->controller->id == 'account' && Yii::$app->controller->action->id == 'subscribe') {
            return parent::beforeSave($this->isNewRecord);
        }
        
        /*if (time() - self::SAVE_DELAY < Yii::$app->session->get(self::SESSSION_LAST_SUBSCRIPTION, 0)) {
            $this->addError('email', 'Слишком часто подписываетесь!');

            return false;
        }*/

        return parent::beforeSave($this->isNewRecord);
    }

    public function beforeValidate()
    {
        //check for subscribe account action
        if (Yii::$app->controller->id == 'account' && Yii::$app->controller->action->id == 'subscribe') {
            return parent::beforeValidate($this->isNewRecord);
        }

        /*if (time() - self::SAVE_DELAY < Yii::$app->session->get(self::SESSSION_LAST_SUBSCRIPTION, 0)) {
            $this->addError('email', 'Слишком часто подписываетесь!');

            return false;
        }*/

        //TODO Поставил $this->isNewRecord так как не сохранялись при изменении подписчики в админке
        return parent::beforeValidate($this->isNewRecord);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Yii::$app->session->set(self::SESSSION_LAST_SUBSCRIPTION, time());
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
