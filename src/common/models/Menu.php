<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $levels
 * @property integer $status
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'levels', 'status'], 'required'],
            [['levels', 'status'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['title'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name' => 'Системное название',
            'title' => 'Описание',
            'levels' => 'Двухуровневое',
            'status' => 'Статус',
        ];
    }


    public function getItems()
    {
        return $this->hasMany(MenuLink::class, ['menu_id' => 'id'])->where(['parent_id' => 0])->orderBy('order ASC');
    }


    public function getItemsArray()
    {
        $items = [];

        foreach($this->items as $item)
        {
            $children = [];
            $activeChild = false;

            if ($this->levels)
                foreach ($item->children as $child) {
                    if (Url::to() == $child->url || strpos(Url::to(), $child->url) !== false)
                        $activeChild = true;

                    $children[] = [
                        'label' => $child->title ? $child->title : '',
                        'url' => $child->url,
                        'linkOptions' => ['class' => $child->class],
                        'active' => (Url::to() == $child->url || strpos(Url::to(), $child->url) !== false),
                        'status' => $child->status,
                        'template' => '<a itemprop="url" href="{url}" class="'.$child->class.'">{label}</a>'
                    ];
                }

            $items[] = [
                'label' => $item->title ? $item->title : '',
                'url' => $item->url,
                'linkOptions' => ['class' => $item->class],
                'options' => ['class' => empty($children) ? '' : 'megamenu-list-title menu-col col-md-3 col-sm-6 col-xs-12'],
                'active' => (Url::to() == $item->url || strpos(Url::to(), $item->url) !== false) || $activeChild,
                'status' => $item->status,
                'items' => empty($children) ? null : $children,
                'template' => empty($children) ? '<a itemprop="url" href="{url}" class="'.$item->class.'">{label}</a>' : '<span class="has-child">{label}</span>',
            ];
        }

        return $items;
    }

    public function search($params=null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);

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

    public function beforeDelete()
    {
        if (!in_array(User::ROLE_ADMIN, array_keys(Yii::$app->user->getRoles()), array_keys(Yii::$app->user->getRoles()))) {
            return false;
        }

        return parent::beforeDelete();
    }
}
