<?php

use common\components\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'attribute' => 'id',
        'contentOptions' => ['style' => 'width:65px;']
    ],
    'ip',
    'name',
    'email',
    'text',
    [
        'attribute' => 'status',
        'value' => function ($data) {
            return $data->status ? "Обработан" : "Не обработан";
        },
        'filter' => [0 => 'Не обработан', 1 => 'Обработан'],
    ],

    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '<div class="btn-group btn-group-xs pull-right">{update} {delete}</div>',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-pjax' => '1',
                    'class' => 'modalForm btn btn-default',
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Ds уверены, что хотите удалить это?'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'class' => 'btn btn-default',
                ]);
            }
        ],
        'contentOptions' => ['style' => 'width:200px; display: block;']
    ],
];
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'toolbar' => [
            ['content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => 'Экспорт',
                        'class' => 'btn btn-default'
                    ],
                    'container' => [
                        'class' => 'btn-group pull-right',
                        'role' => 'group'
                    ],
                    'noExportColumns' => [7],
                ]) .
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role' => 'modal-remote', 'title' => 'Создать', 'class' => 'btn btn-success modalForm']) .
                Html::a('<i title="Обновить таблицу" class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Обновить'])
            ],
            '{pageSize}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'before'=>'<em>'.Yii::t('rbac','* Resize table columns just like a spreadsheet by dragging the column edges.').'</em>',
            'after' => false,
        ],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>

    <p>
        <?= Html::a('Добавить', ['update'], ['class' => 'btn btn-success modalForm']) ?>
    </p>
</div>
