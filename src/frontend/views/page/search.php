<?php


use yii\grid\GridView;
use common\models\Setting;
use yii\helpers\Html;

$main_name = Setting::findOne(['code' => 'main_name', 'status' => 0]);

$this->title = 'Поиск на сайте ' . @$main_name->value;
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Поиск по сайту ' . @$main_name->value]);
$this->registerMetaTag(['name' => 'description', 'content' => 'Поиск материалов по сайту ' . @$main_name->value]);
$this->registerMetaTag(['name' => 'og:title', 'content' => htmlspecialchars('Поиск по сайту ' . @$main_name->value)]);
$this->registerMetaTag(['name' => 'og:description', 'content' => htmlspecialchars('Поиск материалов по сайту ' . @$main_name->value)]);
$this->registerMetaTag(['name' => 'og:url', 'content' => 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' .$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']]);

$this->params['breadcrumbs'][] = 'Поиск по сайту';

?>

<div class="container">
    <h1>Результаты поиска по запросу: <?= Html::encode($query) ?> </h1>
    <section id="search-page">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return
                            Html::tag('p', $data['time_update'], ['class' => 'search-date']) . '' .
                            Html::a($data['name'], $data['url']) . '' .
                            Html::tag('div', $data['description'], ['class' => 'search-desc']);

                    },
                    'label' => false,
                ],
            ],
            'tableOptions' => ['class' => 'table-hover'],
            'pager' => [
                'options' => ['class' => 'pages'],
                'prevPageLabel' => '<i class="ic ic-chevron-left"></i>',
                'nextPageLabel' => '<i class="ic ic-chevron-right"></i>',
                'firstPageLabel' => '<i class="ic ic-step-backward"></i>',
                'lastPageLabel' => '<i class="ic ic-step-forward"></i>',
            ],
            'summary' => false,
        ]);
        ?>
    </section>
</div>
