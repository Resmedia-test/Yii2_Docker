<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * @property integer $status
 * @property integer $id
 * @property string $name
 */
class RequestContact extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%requests_contacts}}';
    }

    public function rules()
    {
        return [
            [['name', 'text', 'email'], 'required'],
            [['time_create', 'status'], 'integer'],
            [['name', 'phone', 'ip', 'email'], 'string', 'max' => 250],
            ['email', 'email'],
            ['text', 'string'],

            /*['name', function ($attribute, $params) {
                if (time() - (int) Yii::$app->session->get('requestContact', 0) <= 300) {
                    $this->addError($attribute, 'Слишком часто отправляете! Попробуйте позже');
                }
            }],*/

            [['id', 'ip'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'ip' => 'IP',
            'name' => 'Имя',
            'text' => 'Текст',
            'email' => 'Email',
            'phone' => 'Телефон',
            'time_create' => 'Время',
            'status' => 'Статус',
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


    public function search($params = [])
    {
        $query = RequestContact::find();

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);

        if(!empty($this->name))
            $query->andFilterWhere(['like', 'name', $this->name]);

        if(is_numeric($this->status))
            $query->andFilterWhere(['status' => $this->status]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize', Yii::$app->cache->get(RequestContact::class . '_pageSize') ? Yii::$app->cache->get(RequestContact::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }


    public function sendEmail()
    {
        $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
        //send to user
        Yii::$app->mailer->compose(['html' => 'notifyUser-html'], ['model' => $this])
            ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта',])
            ->setTo($this->email)
            ->setSubject('Уведомление с сайта ' . $main_name->value ?: 'Название сайта')
            ->send();

        //send to admin
        Yii::$app->mailer->compose(['html' => 'notifyModerator-html'], ['model' => $this])
            ->setFrom([$this->email => $this->name])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Сообщение с сайта ' . $main_name->value ?: 'Название сайта')
            ->send();
    }
}
