<?php

use kartik\widgets\StarRating;
use yii\helpers\Url;
?>

<div class="col-md-3 user">
    <img class="user__img" src="<?= $model->getPreview() ?>">

            <?= StarRating::widget([
                'name' => 'rating' . $model->id,
                'value' => $model->articlesCount > 0 ? @($model->articlesSum / $model->articlesCount) : 0,
                'pluginOptions' => [
                    //'theme' => 'krajee-svg',
                    'size' => 'sm',
                    'displayOnly' => true,
                    'filledStar' => '<span class="icon icon-star-full"></span>',
                    'emptyStar' => '<span class="icon icon-star-empty"></span>',
                    'showCaption' => false,
                ],
                'options' => ['style' => 'display: none;']
            ]); ?>


    <a href="<?= $model->getUrl() ?>" data-pjax="0">
        <h2 class="user__name"><?= $model->getFullName() ?></h2>
    </a>

        <a href="<?= $model->articlesCount ? Url::to(['/article', 'userId' => $model->id]) : '#' ?>" data-pjax="0">
            <?= Yii::t('app', '{n, plural, =0{Нет статей} one{# Статья} other{# Статьи} many{# Статей}}', ['n' => $model->articlesCount]) ?>
        </a>
        <br>
        <br>
</div>

