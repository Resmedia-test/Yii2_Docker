<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use v0lume\yii2\metaTags\MetaTagBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "sections".
 *
 * @property integer $id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $name
 * @property string $url
 * @property integer $status
 * @property integer $time_update
 */
class Section extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sections}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module', 'action', 'name', 'url'], 'required'],
            [['status', 'time_update'], 'integer'],
            [['module', 'controller', 'action', 'name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => 'Модуль',
            'controller' => 'Контроллер',
            'action' => 'Action',
            'name' => 'Название на странице',
            'url' => 'Url',
            'status' => 'Статус',
            'time_update' => 'Время обновления',
        ];
    }

    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::className(),
            ],
            'TimeStamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'time_update',
                'updatedAtAttribute' => 'time_update',
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'dataClosure' => function ($model) {
                    return [
                        'loc' => Url::to($model->url, true),
                        'lastmod' => strtotime($model->time_update),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    public function getMeta()
    {
        $meta = $this->getBehavior('MetaTag');

        return !empty($meta->title && $meta->keywords && $meta->description);
    }

    public static function listModules()
    {
        $result = [];
        $data = array_keys(Yii::$app->modules);

        foreach ($data as $key) {
            if ($key !== 'gridview' && $key !== 'debug' && $key !== 'gii') {
                $result[$key] = $key;
            }
        }

        return $result;
    }
}
