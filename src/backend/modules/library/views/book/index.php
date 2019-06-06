<?php

use common\models\Section;
use common\models\User;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\Book;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Библиотека';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'parent_id',
                'value' => function($model) {
                    return isset($model->parent) ? $model->parent->name : '';
                },
            ],
            'name',
            'url',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{status} {update} {delete}</div>',
                'buttons' => [
                    'status' => function ($url, $model, $key) {
                        $result = '<button data-toggle="dropdown" class="btn btn-'.($model->status ? 'success' : 'danger').' dropdown-toggle">
                            <i class="glyphicon glyphicon-eye-'.($model->status ? 'open' : 'close').'" title="Опубликовано"></i> <b class="caret"></b>
                        </button>';

                        $result .= Dropdown::widget([
                            'items' => [
                                [
                                    'label' => '<i class="glyphicon glyphicon-eye-'.($model->status ? 'close' : 'open').'"></i>',
                                    'url' => Url::to([
                                        'set',
                                        'id' => $model->id,
                                        'attr' => 'status',
                                        'val' => $model->status ? 0 : 1,
                                    ]),
                                ]
                            ],
                            'encodeLabels' => false
                        ]);


                        return $result;
                    },
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
                'contentOptions' => ['style' => 'display: block;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return [
                'class' => !$model->status ? 'danger' : '',
            ];
        },
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role'=>'modal-remote','title'=> 'Создать','class'=>'btn btn-success modalForm']).
                Html::a('<i title="Обновить таблицу" class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Обновить'])
            ],
            '{pageSize}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            'after' => false,
        ],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
