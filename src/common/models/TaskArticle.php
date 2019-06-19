<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 06.12.16
 * Time: 3:27
 */

namespace common\models;


use yii\db\ActiveRecord;

class TaskArticle extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tasks_articles}}';
    }

    public function rules()
    {
        return [
            [['time', 'text', 'models'], 'required'],
            ['time', 'integer'],
            [['text', 'models'], 'string'],
        ];
    }
}