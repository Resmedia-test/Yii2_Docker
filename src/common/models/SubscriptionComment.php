<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscriptions_comments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $model
 * @property integer $model_id
 * @property integer $type_id
 * @property integer $comments
 */
class SubscriptionComment extends \yii\db\ActiveRecord
{
    //TODO сдесь изменены только названия, но сама суть остается как прежде, нужно сделать подписку с учетом новых изменений
    const TYPE_NONE = 0;
    const TYPE_NOW = 1;
    const TYPE_REPLY = 3;

    static $types = [
        self::TYPE_NONE => 'Не уведомлять',
        self::TYPE_NOW => 'Обо всех новых',
        self::TYPE_REPLY => 'Только при ответе',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriptions_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'model_id', 'type_id'], 'integer'],
            [['model'], 'required'],
            [['model'], 'string', 'max' => 255],
            [['comments'], 'integer'],

            ['type_id', 'in', 'range' => array_keys(self::$types)],

            ['user_id', 'unique', 'targetAttribute' => ['user_id', 'model', 'model_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'type_id' => 'Type ID',
            'comments' => 'Comments',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getEntity()
    {
        return $this->hasOne($this->model, ['id' => 'model_id']);
    }
}
