<?php

use common\models\Article;
use common\models\User;
use kartik\export\ExportMenu;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $filterModel \common\models\Article */

$this->title = 'Публикации';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    'id',
    [
        'attribute' => 'time_create',
        'value' => function ($model) {
            return empty($model->time_create) ? null : $model->time_create;
        },
        'format' => 'datetime',
        'options' => ['style' => 'width:175px;'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'locale' => ['format' => 'YYYY-MM-DD'],
            ],
        ],
    ],
    [
        'attribute' => 'user_id',
        'value' => function ($model) {
            return isset($model->user) ? $model->user->getFullName() : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'data' => User::listAll('Выберите пользователя'),
        ],
    ],
    'name',
    /*[
        'format' => 'raw',
        'attribute' => 'Валидация',
        'value' => function ($model) {
            if($model->validate()) {
                return 'ok';
            } else {
                foreach ($model->errors as $errors){
                    foreach ($errors as $error) {
                        return '<div class="text-danger">' . $error . '</div>';
                    }
                } ;
            }
        }
    ],*/
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '<div class="btn-group btn-group-xs pull-right">{status} {article_main} {update} {restore} {delete}</div>',
        'buttons' => [
            'status' => function ($url, $model, $key) {
                /** @var $model \common\models\Article */
                $result = $model->typeStatus($model->status);

                $result .= Dropdown::widget([
                    'items' => [
                        [
                            'label' => '<i class="glyphicon glyphicon-eye-' . ($model->status === Article::STATUS_PUBLISHED ? 'close' : 'open') . '"></i>',
                            'url' => Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => $model->status ? Article::STATUS_UNPUBLISHED : Article::STATUS_PUBLISHED,
                            ]),
                        ],
                        [
                            'label' => '<i class="glyphicon glyphicon-lock"></i>',
                            'url' => Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Article::STATUS_MODERATION,
                            ]),
                        ],
                    ],
                    'encodeLabels' => false
                ]);


                return $result;
            },

            'article_main' => function ($url, $model, $key) {
                return Html::a(
                    '<span class="glyphicon glyphicon-info-sign"></span>',
                    Url::to([
                        'set',
                        'id' => $model->id,
                        'attr' => 'article_main',
                        'val' => $model->article_main ? 0 : 1,
                    ]),
                    [
                        'title' => $model->article_main ? "Снять статью с главной" : 'Поставить на главную',
                        'class' => 'btn ' . ($model->article_main ? 'btn-danger' : 'btn-default'),
                    ]
                );
            },

            'update' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-pjax' => '1',
                    'class' => 'modalForm btn btn-default',
                ]);
            },

            'restore' => function ($url, $model, $key) {
                if (!Yii::$app->user->superUser() && $model->status === Article::STATUS_DELETED) {
                    return '';
                }

                return Html::a(
                    '<span class="' . ($model->status === Article::STATUS_DELETED ? 'glyphicon glyphicon-share-alt' : 'glyphicon glyphicon-trash') . '"></span>',
                    Url::to([
                        'set',
                        'id' => $model->id,
                        'attr' => 'status',
                        'val' => $model->status !== Article::STATUS_DELETED ? Article::STATUS_DELETED : Article::STATUS_UNPUBLISHED,
                    ]),
                    [
                        'title' => $model->status === Article::STATUS_DELETED ? 'Восстановить' : 'Удалить',
                        'class' => 'btn btn-default',
                    ]
                );
            },
            'delete' => function ($url, $model, $key) {
                if (!Yii::$app->user->superUser()) {
                    return '';
                }

                return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Ds уверены, что хотите удалить это?'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'class' => 'btn btn-default',
                ]);
            }
        ],
        // 'contentOptions' => ['style' => 'width:250px; display: block;']
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
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return ['class' => $model->status === Article::STATUS_DELETED ? 'danger' : ''];
        },
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role' => 'modal-remote', 'title' => 'Создать', 'class' => 'btn btn-success modalForm']) .
                Html::a('<i title="Обновить таблицу" class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Обновить']) .
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => 'Экспорт',
                        'class' => 'btn btn-success'
                    ],
                    'container' => [
                        'class' => 'btn-group',
                        'role' => 'group'
                    ],
                    'noExportColumns' => [],
                ]),
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
