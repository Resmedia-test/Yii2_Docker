<?php

namespace common\models;

use Yii;
use v0lume\yii2\metaTags\MetaTagBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "books".
 *
 * @property integer $id
 * @property integer $section_id
 * @property integer $parent_id
 * @property string $url
 * @property string $name
 * @property string $small_desc
 * @property string $full_desc
 * @property integer $time_create
 * @property integer $time_update
 * @property integer $status
 */
class Book extends ActiveRecord
{
    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    static $statuses = [
        self::STATUS_NOT_PUBLISHED => 'Нет',
        self::STATUS_PUBLISHED => 'Да',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%books}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'name', 'small_desc', 'full_desc'], 'required'],
            [['section_id', 'parent_id', 'status', 'time_create', 'time_update'], 'integer'],
            [['small_desc', 'full_desc'], 'string'],
            [['url', 'name'], 'string', 'max' => 255],
            [['parent_id'], 'default', 'value' => 0],

            [['section_id', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'section_id' => 'Раздел',
            'parent_id' => 'Родитель',
            'url' => 'Url',
            'name' => 'Название материала',
            'small_desc' => 'Краткое описание',
            'full_desc' => 'Текст',
            'status' => 'Статус',
            'time_create' => 'Дата создания',
            'time_update' => 'Дата обновления',
        ];
    }

    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'time_update',
            ],
            'sitemap' => [
                'class' => \himiklab\sitemap\behaviors\SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    if (date('Y', $model->time_create) !== date('Y')) {
                        return false;
                    }

                    return [
                            'loc' => Url::to($model->getUrl(), true),
                            'lastmod' => $model->time_update,
                            'changefreq' => \himiklab\sitemap\behaviors\SitemapBehavior::CHANGEFREQ_DAILY,
                            'priority' => 0.8
                        ];
                    }
            ],
        ];
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getSection()
    {
        return $this->hasOne(Section::class, ['id' => 'section_id']);
    }

    public function isPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }

    public function getStatuses()
    {
        return self::$statuses;
    }

    public function beforeDelete()
    {
        if (!in_array(User::ROLE_ADMIN, array_keys(Yii::$app->user->getRoles()))) {
            return false;
        }

        return parent::beforeDelete();
    }

    public function getUrl()
    {
        $result = isset($this->section) ? '/' . $this->section->url : '';
        $result .= isset($this->parent) ? '/' . $this->parent->url : '';
        $result .= '/' . (!empty($this->url) ? $this->url : $this->id);

        return $result;
    }

    public function searchUser($params, $section_id)
    {
        $this->load($params);
        $query = self::find()
            ->where(['status' => self::STATUS_PUBLISHED, 'parent_id' => 0])
            ->andWhere(['section_id' => $section_id]);

        //adjust the query by adding the filters
        if(!empty($this->name))
            $query->andWhere('(
                name LIKE "%'.$this->name.'%"
                OR
                small_desc LIKE "%'.$this->name.'%"
                OR
                full_desc LIKE "%'.$this->name.'%"
            )');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $dataProvider;
    }
}
