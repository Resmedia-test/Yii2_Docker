<?php

namespace common\models;

use common\components\helpers\StringHelper;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use v0lume\yii2\metaTags\MetaTagBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use zxbodya\yii2\galleryManager\GalleryBehavior;

/**
 * This is the model class for table "{{%articles}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $small_desc
 * @property string $full_desc
 * @property integer $time_create
 * @property integer $time_update
 * @property integer $time_publish
 * @property integer $views
 * @property integer $comments
 * @property integer $status
 * @property string $url
 * @property integer $important
 * @property double $rate
 * @property integer $rates
 */
class Article extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%articles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'small_desc', 'full_desc'], 'required'],

            [['user_id', 'status', 'time_create', 'time_publish', 'time_update', 'views', 'article_main', 'rates'], 'integer'],
            [['small_desc', 'full_desc', 'url'], 'string'],
            [['name', 'url'], 'string', 'max' => 255],
            ['url', 'unique', 'message' => 'Такой URL уже есть!'],
            ['rate', 'double'],
            [['views'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'user_id' => 'Пользователь',
            'name' => 'Название статьи',
            'article_main' => 'На главной',
            'small_desc' => 'Краткое описание статьи',
            'full_desc' => 'Текст статьи',
            'time_create' => 'Дата создания',
            'time_update' => 'Дата обновления',
            'time_publish' => 'Время публикации',
            'status' => 'Опубликовано',
            'views' => 'Просмотры',
            'url' => 'Url',
            'rate' => 'Средняя оценка',
            'rates' => 'Количество оценок',
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
                'class' =>  SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    if (date('Y', $model->time_create) !== date('Y')) {
                        return false;
                    }
                    return [
                        'loc' => Url::to($model->getUrl(), true),
                        'lastmod' => $model->time_update,
                        'changefreq' =>  SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
            'galleryBehavior' => [
                'class' => GalleryBehavior::class,
                'type' => 'article',
                'extension' => 'jpg',
                // image dimmentions for preview in widget
                'previewWidth' => 300,
                'previewHeight' => 200,
                // path to location where to save images
                'directory' => Yii::getAlias('@frontend') . '/web/uploads/articles',
                'url' => Yii::$app->params['domainFrontend'] . '/uploads/articles',
                // additional image versions
                'versions' => [
                    'i600x328' => function ($img) {
                        $width = 600;
                        $height = 328;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                    'i200x120' => function ($img) {
                        $width = 200;
                        $height = 120;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                    'i220x100' => function ($img) {
                        $width = 220;
                        $height = 100;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                    'i160x120' => function ($img) {
                        $width = 160;
                        $height = 120;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                ]
            ]
        ];
    }

    static function typeStatus($status)
    {
        $result = '';
        switch($status) {
            case self::STATUS_DELETED:
                return $result = '
                        <button data-toggle="dropdown" class="btn  btn-danger dropdown-toggle">
                           <i class="glyphicon glyphicon-trash" title="Удалено"></i>
                           <b class="caret"></b>
                        </button>';
            case self::STATUS_MODERATION:
                return $result = '
                        <button data-toggle="dropdown" class="btn  btn-warning dropdown-toggle">
                           <i class="glyphicon glyphicon-lock" title="Модерация"></i>
                           <b class="caret"></b>
                        </button>';
            case self::STATUS_UNPUBLISHED:
                return $result = '
                        <button data-toggle="dropdown" class="btn  btn-danger dropdown-toggle">
                           <i class="glyphicon glyphicon-eye-close" title="Скрыто"></i>
                           <b class="caret"></b>
                        </button>';
            case self::STATUS_PUBLISHED:
                return $result = '
                        <button data-toggle="dropdown" class="btn  btn-success dropdown-toggle">
                           <i class="glyphicon glyphicon-eye-open" title="Опубликовано"></i>
                           <b class="caret"></b>
                        </button>';
        }
        return $result;
    }

    public function compareDate($year, $month, $day)
    {
        if(date('Y', $this->time_create) !== $year)
            return false;

        if(date('m', $this->time_create) !== $month)
            return false;

        if(date('d', $this->time_create) !== $day)
            return false;

        return true;
    }

    public function getCommentsCount()
    {
        return count(Comment::findAll(['model' => 'Article', 'model_id' => $this->id]));
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUrl()
    {
        $result = '/articles/' . date('Y', $this->time_create) . '/' . date('m', $this->time_create) . '/' . date('d', $this->time_create) . '/' . $this->url ?: $this->id;

        return $result;
    }

    public function beforeSave($insert)
    {
        // TODO New URL generator if the same url includes in base

        if(!$this->url){
            $this->url = StringHelper::translit($this->name);
        }

        return parent::beforeSave($this->isNewRecord);
    }

    public function beforeDelete()
    {
        if (!Yii::$app->user->superUser()) {
            return false;
        }

        return parent::beforeDelete();
    }

    public static function listAll($prompt=false)
    {
        $result = [];

        if($prompt)
            $result[0] = 'Не выбрано';

        $models = self::find()->all();

        foreach($models as $model)
            $result[$model->id] = $model->name;

        return $result;
    }

    public function search($params=null)
    {
        $query = self::find();
        //$query->joinWith(['assignment']);

        if(isset($params))
            $this->load($params);

        if ($this->time_create && strpos($this->time_create, ' - ')) {
            $time = explode(' - ', $this->time_create);

            $query->andFilterWhere(['between', 'time_create', strtotime($time[0]), strtotime($time[1])]);
        }

        //adjust the query by adding the filters
        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 'name',$this->name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize', Yii::$app->cache->get(self::class . '_pageSize') ?
                    Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }

    public function addRate($rate)
    {
        $this->rate = (($this->rate * $this->rates) + $rate) / ++$this->rates;
        $this->updateAttributes(['rate', 'rates']);
    }
}
