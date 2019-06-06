<?php

use common\components\GridView;
use common\models\Page;

use yii\helpers\Html;

use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = ['label' => 'Контент', 'url' => ['/content']];
$this->params['breadcrumbs'][] = $this->title;


$template = '{update} {pre-delete}'; //{width}

if (Yii::$app->user->superUser()) {

    $template .= '{restore} {delete}';
}
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'columns' => [

            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return
                        Html::a(
                            $model->title,
                            preg_replace("#/$#", "", Yii::$app->params['domainFrontend']). DIRECTORY_SEPARATOR. $model->url,
                            [
                                'target' => '_blank',
                                'data-pjax' => 0
                            ]
                        );
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">'.$template.'</div>',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '1',
                            'class' => 'modalForm btn btn-default',
                        ]);
                    },
                    'restore' => function ($url, $model, $key) {
                        if ($model->status !== Page::STATUS_DELETED)
                            return '';

                        return Html::a(
                            '<span class="glyphicon glyphicon-share-alt"></span>',
                            \yii\helpers\Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Page::STATUS_NOT_PUBLISHED,
                            ]),
                            [
                                'title' => "Восстановить",
                                'class' => 'btn btn-default',
                            ]
                        );
                    },
                    'pre-delete' => function ($url, $model, $key) {

                        if ($model->status == Page::STATUS_DELETED) {
                            return '';
                        }

                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            \yii\helpers\Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Page::STATUS_DELETED,
                            ]),
                            [
                                'title' => "Удалить",
                                'class' => 'btn btn-default',
                            ]
                        );
                    },
                    'delete' => function ($url, $model, $key) {

                        if (!Yii::$app->user->superUser()){
                            return '';
                        }

                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                            'title' => 'Полное удаление',
                            'data-confirm' => Yii::t('yii', 'Вы уверены, что хотите удалить это?'),
                            'data-method' => 'post',
                            'data-pjax' => '1',
                            'class' => 'btn btn-default',
                        ]);
                    }
                ],
                'contentOptions' => ['style' => 'width:165px;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return [
                'class' => $model->isDeleted() ? 'danger' : '',
                'hidden' => $model->isDeleted() ? !Yii::$app->user->superUser() : false,

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
            //'before'=>'<em>'.Yii::t('rbac','* Resize table columns just like a spreadsheet by dragging the column edges.').'</em>',
            'after' => false,
        ],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
