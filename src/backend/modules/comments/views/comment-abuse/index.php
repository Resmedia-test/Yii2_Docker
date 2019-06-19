<?php

use yii\helpers\Html;
use common\components\GridView;
use yii\widgets\Pjax;
use common\models\Comment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жалобы';
$this->params['breadcrumbs'][] = ['label' => 'Комментарии', 'url' => ['/comments']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'comment_id',
            'comment.text',
            'user_id',
            'user.fullName',
            'time_create:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{delete}</div>',
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
                'contentOptions' => ['style' => 'width:65px;']
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
