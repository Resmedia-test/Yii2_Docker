<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use ReflectionClass;
use v0lume\yii2\metaTags\MetaTagBehavior;
use v0lume\yii2\metaTags\models\MetaTag;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $content
 * @property integer $time_update
 * @property integer $status
 */
class Page extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    static $labels = [
        self::STATUS_PUBLISHED => 'Опубликовано',
        self::STATUS_NOT_PUBLISHED => 'Не опубликовано',
        self::STATUS_DELETED => 'Удалено',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    public function extraFields()
    {
        return ['_metaTags'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['time_update', 'status'], 'integer'],
            [['url', 'title'], 'string', 'max' => 255],
            ['url', 'unique', 'message' =>'Такой урл уже есть'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'title' => 'Название',
            'content' => 'Текст',
            'time_update' => 'Время обновления',
            'status' => 'Статус',
        ];
    }


    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::class,
            ],
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    if (date('Y', $model->time_update) !== date('Y')) {
                        return false;
                    }

                    if($model->status !== self::STATUS_PUBLISHED){
                        return false;
                    }

                    return [
                        'loc' => Yii::$app->params['domainFrontend'] . DIRECTORY_SEPARATOR . Url::to($model->url),
                        'lastmod' => $model->time_update,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_HOURLY,
                        'priority' => 1.0
                    ];
                }
            ],
        ];
    }


    public function get_metaTags()
    {
        return $this->hasOne(MetaTag::class, ['model_id' => 'id'])
            ->andOnCondition(['model' => (new ReflectionClass($this))->getShortName()])
            ->select(['title', 'keywords', 'description']);
    }


    public function search($params=null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);

        if (!Yii::$app->user->superUser()) {
            $query->andFilterWhere(['<>', 'status', self::STATUS_DELETED]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize', Yii::$app->cache->get(self::class . '_pageSize') ? Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isNotPublished()
    {
        return $this->status == self::STATUS_NOT_PUBLISHED;
    }

    public function isPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }

    public static function getStatuses($status = null)
    {
        return isset($status) && isset(self::$labels[$status]) ? self::$labels[$status] : self::$labels;
    }
}
