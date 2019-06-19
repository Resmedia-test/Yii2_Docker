<?php

namespace common\components;

use Yii;
use yii\helpers\Url;


class GridView extends \kartik\grid\GridView
{
    public $layout = "{pageSize}{buttons}<br><br>{summary}\n{items}\n{pager}";

    public $sortable = false;
    public $bordered = false;

    public $buttons = [];
    public $export = false;


    public function run()
    {
        $models = $this->dataProvider->getModels();

        if(!empty($models))
        {
            $model = $models[0];

            $cacheId = get_class($model).'_pageSize';

            $value = Yii::$app->cache->get($cacheId);

            if(!$value || Yii::$app->request->get('pageSize'))
            {
                $value = Yii::$app->request->get('pageSize', 10);
                Yii::$app->cache->set($cacheId, $value);
            }
        }

        parent::run();
    }


    public function renderSection($name)
    {
        switch ($name) {
            case "{pageSize}":
                return $this->renderPageSize();
            case "{buttons}":
                return $this->renderButtons();
            default:
                return parent::renderSection($name);
        }
    }


    public function renderPageSize()
    {
        $result = '';
        $lastSize = 10;
        $totalCount = 0;
        $value = 0;

        $items = [];

        $models = $this->dataProvider->getModels();

        if(!empty($models))
        {
            $model = $models[0];

            $cacheId = get_class($model).'_pageSize';

            $value = Yii::$app->cache->get($cacheId);
            $totalCount = $this->dataProvider->getTotalCount();


            while($lastSize < $totalCount)
            {
                array_push($items, [
                    'label' => $lastSize,
                    'url' => Url::to(['', 'pageSize' => $lastSize]),
                    'linkOptions' => ['data-pjax' => 0],
                ]);
                $lastSize *= 2;
            }
        }

        array_push($items, [
            'label' => 'Все',
            'url' => Url::to(['', 'pageSize' => $totalCount]),
            'linkOptions' => ['data-pjax' => 0],
        ]);



        $result = '
        <div class="dropdown pull-right">
            <a data-target="#" href="/" data-toggle="dropdown" class="dropdown-toggle btn btn-default">'.$value.' <b class="caret"></b></a>
        ';

        $result .= \yii\bootstrap\Dropdown::widget([
            'items' => $items,
        ]);

        $result .= '
        </div>
        ';

        return $result;
    }


    public function renderButtons()
    {
        $result = '';

        foreach($this->buttons as $button)
            $result .= $button;

        return $result;
    }

}