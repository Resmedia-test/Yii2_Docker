<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $element
 * @property integer $status
 */
class Setting extends \yii\db\ActiveRecord
{
    const EMAIL_MODERATOR = 'moderatorEmail';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'element'], 'required'],
            [['value', 'element'], 'string'],
            [['status'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['code'], 'unique'],
            [['name'], 'unique'],
            ['value', 'default', 'value' => 'Content']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'code' => 'Системное название',
            'name' => 'Описание',
            'value' => 'Значение',
            'element' => 'Тип значения',
            'status' => 'Статус',
        ];
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getElement()
    {

        return $this->element;
    }
}
