<?php
namespace common\components;

use Yii;
use yii\bootstrap\Dropdown;
use yii\helpers\Url;

class SortableGridView extends \himiklab\sortablegrid\SortableGridView
{
    public $layout = "
           <div class='grid-header'>{header}\n{pageSize}</div>\n
             {summary}\n
             {items}\n
             {pager}\n
             {footer}";

    public $buttons = [];
    public $sortable = false;
    public $bordered = false;
    public $pageSize = true;
    public $header = [];
    public $footer = 'Перетаскивайте панели, чтобы поменять порядок расстановки материалов';


    public function run()
    {
        if($this->sortable)
            $this->registerWidget();

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
                return $this->pageSize ? $this->renderPageSize() : null;
            case "{header}":
                return $this->renderHeader();
            case "{footer}":
                return $this->renderFooter();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderPageSize()
    {
        $result = '';

        $lastSize = 10;
        $totalCount = 10;
        $value = 10;

        $items = [];

        $models = $this->dataProvider->getModels();

        if(!empty($models))
        {
            $model = $models[0];
            $cacheId = get_class($model).'_pageSize';

            $value = Yii::$app->cache->get($cacheId);
            $totalCount = $this->dataProvider->getTotalCount();
        }

        while($lastSize < $totalCount)
        {
            array_push($items, [
                'label' => $lastSize,
                'url' => Url::to(['', 'pageSize' => $lastSize]),
                'linkOptions' => ['data-pjax' => 0],
            ]);
            $lastSize *= 2;
        }

        array_push($items, [
            'label' => 'Все',
            'url' => Url::to(['', 'pageSize' => $totalCount]),
            'linkOptions' => ['data-pjax' => 0],
        ]);

        $result = '
        <div class="dropdown pull-right">
            <a data-target="" href="/" data-toggle="dropdown" class="dropdown-toggle btn btn-default">'.$value.' <b class="caret"></b></a>
        ';

        $result .= Dropdown::widget([
            'items' => $items,
        ]);

        if($this->buttons){
            foreach ($this->buttons as $button){
                $result .= $button;
            };
        }

        $result .= '
        </div>
        ';

        return $result;
    }


    public function renderHeader()
    {
        $result = '';

        foreach($this->header as $head)
            $result .= $head;

        return $result;
    }

    public function renderFooter()
    {
        return '<div class="help-block">' . $this->footer . '</div>';
    }
}