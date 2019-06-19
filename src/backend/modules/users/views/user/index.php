<?php

use common\models\User;
use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            [
                'attribute' => 'id',
                'filterOptions' => ['style' => 'width:80px'],
            ],
            [
                'attribute' => 'role',
                'value' => function($model){
                    /** @var $model \common\models\User */
                    return User::$roles[$model->getUserRole($model->id)];
                },
                'filter' => User::$roles,
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return isset(User::$statuses[ $model->status ]) ? User::$statuses[ $model->status ] : null;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['multiple' => true],
                ],
                'filter' => User::$statuses,
                'filterOptions' => ['style' => 'max-width:350px'],
            ],
            'email',
            'name',

            'lastname',
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
                                Url::to([
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
                'contentOptions' => ['style' => 'width:200px; display: block;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class' => $model->status == User::STATUS_DELETED ? 'danger' : ''];
        },
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
