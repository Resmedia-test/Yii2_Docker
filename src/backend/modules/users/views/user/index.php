<?php

use common\models\User;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use common\components\GridView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'attribute' => 'id',
        'filterOptions' => ['style' => 'width:80px'],
    ],
    [
        'attribute' => 'role',
        'value' => function ($model) {
            return isset(User::$roles[$model->role]) ? User::$roles[$model->role] : null;
        },
        'filter' => User::$roles,
    ],
    [
        'attribute' => 'status',
        'value' => function ($model) {
            return isset(User::$statuses[$model->status]) ? User::$statuses[$model->status] : null;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['multiple' => true],
        ],
        'filter' => User::$statuses,
        'contentOptions' => ['style' => 'width:195px;']
    ],
    'email',
    'name',
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
                if ($model->id == Yii::$app->user->id) {
                    return false;
                }

                if ($model->status !== User::STATUS_DELETED) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '1',
                        'class' => 'btn btn-default',
                    ]);
                } else {
                    return Html::a('<span class="glyphicon glyphicon-share-alt"></span>',
                        \yii\helpers\Url::to([
                            'set',
                            'id' => $model->id,
                            'attr' => 'status',
                            'val' => User::STATUS_ACTIVE,
                        ]),
                        [
                            'title' => "Восстановить",
                            'class' => 'btn btn-default',
                        ]
                    );
                }
            }
        ],
        'contentOptions' => ['style' => 'display: block;']
    ],
];
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => $columns,
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class' => $model->status == User::STATUS_DELETED ? 'danger' : ''];
        },
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
                    'noExportColumns' => [0],
                ]).
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role'=>'modal-remote','title'=> 'Создать','class'=>'btn btn-success modalForm']).
                Html::a('<i title="Обновить таблицу" class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Обновить']),
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
</div>
