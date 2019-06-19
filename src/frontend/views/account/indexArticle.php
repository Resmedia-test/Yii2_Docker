<?php

use common\components\GridView;
use common\models\Article;
use common\models\User;
use frontend\widgets\ArticleList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Мои публикации';
$this->params['breadcrumbs'][] = ['url'=>'/account', 'label'=>'Кабинет'];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">
    <div class="col-md-12">
        <div class="light-gray">
            <i class="ic ic-info-sign"></i>
            Все статьи до момента публикации рассматриваются нашими редакторами в соответствии с
            <a class="" href="/pravila" data-original-title="" title="">правилами</a> и общепринятыми нормами.
            Скорость рассмотрения зависит от загруженности редакторов и количества статей на рассмотрении, но не более двух дней.
        </div>
        <br>

        <?php Pjax::begin(['id' => 'refresh']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'name',
                [
                    'attribute' => 'status',
                    'header' => 'Опубликовано',
                    'value' => function ($data) {
                        return $data->status === Article::STATUS_PUBLISHED ? "Да" : "Нет";
                    },
                    'filter' => [0 => 'Нет', 1 => 'Да'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="btn-group btn-group-xs pull-right">{update}</div>',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            $url = Url::to(['update-article', 'id' => $model->id]);

                            return Html::a('<span class="ic ic-pencil"></span>', $url, [
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                                'class' => 'modalForm btn btn-default',
                            ]);
                        },
                    ],
                    'contentOptions' => ['style' => 'width:65px;']
                ],
            ],
            'pager' => [
                'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
            ],
            'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
            'summary' => false,
            'buttons' => [
                Html::a(
                    'Опубликовать статью', ['update-article'],
                    ['class' => 'btn btn-default modalForm ' . (Yii::$app->user->getStatus() == User::STATUS_EMAIL_NC ? 'hide' : '')])
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>


