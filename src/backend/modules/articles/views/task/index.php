<?php

use common\models\Article;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\components\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рассылка';
$this->params['breadcrumbs'][] = $this->title;

$template = '{delete}';

$model = new \common\models\TaskArticle();
$model->time = date('d.m.Y H:i');

$dataProviderNews = new \yii\data\ActiveDataProvider([
    'query' => Article::find(),
    'sort' => [
        'defaultOrder' => ['id' => SORT_DESC],
    ],
    'pagination' => [
        'pageSize' => 30,
    ]
]);
?>
<br>
<br>
<div class="newsTask-index">
    <h3>Текущие задания:</h3>

    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'time',
                'value' => function($model) {
                    return empty($model->time) ? null : date('d.m.Y в H:i', $model->time);
                },
                'format' => 'raw',
                'options' => ['style' => 'width:175px;']
            ],
            'text',
            [
                'attribute' => 'models',
                'value' => function($model) {
                    return $model->models;
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">' . $template . '</div>',
                'buttons' => [
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
                'contentOptions' => ['style' => 'width:205px;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<br><br>

<div class="news-index">
    <h3>Добавление задания:</h3>

    <?php $form = ActiveForm::begin([
        'id' => 'articleSubscription-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])->widget(CKEditor::class, [
        'id' => 'content',
        'clientOptions' => ['allowedContent' => true, 'height' => '200px'],
        'preset' => 'full',
    ])->label('Текст'); ?>

    <?= $form->field($model, 'time', ['options' => ['class' => 'form_group']])
        ->widget(DateTimePicker::class, [
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy hh:ii',
            ],
        ])->label('Время отправки') ?>

    <?php Pjax::begin(['id' => 'refreshNews']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProviderNews,
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'TaskArticle[models]',
                    'options' => ['style' => 'width:50px']
                ],
                [
                    'attribute' => 'time_create',
                    'format' => 'datetime',
                    'options' => ['style' => 'width:205px']
                ],
                [
                    'attribute' => 'id',
                    'format' => 'raw',
                    'options' => ['style' => 'width:75px']
                ],
                'name',
                'views'
            ],
            'pager' => [
                'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
            ],
            'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
            'summary' => false,
        ]); ?>
    <?php Pjax::end(); ?>

    <p class="text-center">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </p>
    <?php ActiveForm::end(); ?>
</div>
