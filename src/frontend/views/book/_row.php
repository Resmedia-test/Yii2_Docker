<?php

use common\components\helpers\StringHelper;

Yii::$app->language = 'ru-RU';

/** @var $model \common\models\Book */
?>

<div class="items-list">
    <h2 class="items-list__title">
        <a href="<?= $model->getUrl() ?>" data-pjax="0">
            <?= $model->name ?>
        </a>
    </h2>
    <p class="items-list__desc">
        <?= StringHelper::truncate($model->small_desc, 250) ?>
    </p>
    <span class="items-list__date">
        <?= Yii::$app->formatter->asDatetime($model->time_create, 'd MMM Y') ?>
    </span>
</div>
