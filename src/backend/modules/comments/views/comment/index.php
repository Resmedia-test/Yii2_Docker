<?php

use common\models\User;
use yii\helpers\Html;
use common\components\GridView;
use yii\widgets\Pjax;
use common\models\Comment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            'model_id',
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    return isset($model->user) ? $model->user->getFullName() : '';
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'data' => User::listAll('Выберите пользователя'),
                ],
            ],
            [
                'attribute' => 'time_create',
                'value' => function($model) {
                    return empty($model->time_create) ? null : $model->time_create;
                },
                'format' => 'datetime',
                'options' => ['style' => 'width:175px;'],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale'=>['format' => 'YYYY-MM-DD'],
                    ],
                ],
            ],
            'model',
            [
                'attribute' => 'text',
                'value' => function($model) {
                    $result = strip_tags($model->text);
                    $result = \common\components\helpers\StringHelper::truncate($result, 100);

                    return $result;
                }
            ],
            // 'text',
            'ip',
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
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '1',
                            'class' => 'btn btn-default',
                        ]);
                    }
                ],
                'contentOptions' => ['style' => 'width:250px; display: block;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class' => $model->status ? 'danger' : ''];
        },
        'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['update'], ['class' => 'btn btn-success modalForm']).
                Html::a('<i title="Обновить таблицу" class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Обновить'])
            ],
            '{pageSize}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'before'=>'<h4>Регион</h4>',
            'after' => false,
        ],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
