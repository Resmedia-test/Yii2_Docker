<?php


use common\components\SortableGridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ссылки меню';
$this->params['breadcrumbs'][] = ['label' => 'Контент', 'url' => ['/content']];
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['/content/menu/index']];
$this->params['breadcrumbs'][] = $this->title.' #'.$menu->id;
?>


<div class="menu-link-index">

    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'order',
            [
                'attribute' => 'parent_id',
                'value' => function ($data) {
                    return isset($data->parent) ? $data->parent->title : "";
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'title',
                'value' => function ($data) {
                    return $data->parent_id ? "&nbsp;&nbsp;&nbsp;&nbsp;".$data->title : $data->title;
                },
                'encodeLabel' => false,
                'format' => 'raw',
            ],
            [
                'attribute'=>'url',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a($data->url, Url::to($data->url, true));
                },
            ],
            // 'class',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{update} {delete}</div>',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/content/menu-link/update', 'menu_id' => $model->menu_id, 'id' => $model->id], [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'class' => 'modalForm btn btn-default',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['delete', 'id' => $model->id, 'menu_id' => $model->menu_id]), [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'class' => 'btn btn-default',
                        ]);
                    }
                ],
                'contentOptions' => ['style' => 'width:130px;']
            ],
        ],
        'header' => [
            Html::label('<b>Меню: </b>' . $menu->title,
                'name', ['class' => 'helpCase-name']).
            Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить',
                ['update', 'menu_id' => $menu->id],
                ['class' => 'btn btn-success pull-right modalForm'])
        ],
        //'pageSize' => false,
        'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class' => !$model->status ? 'danger' : ''];
        },
        'summary' => false,
    ]); ?>


    <?php Pjax::end(); ?>

</div>

