<?php

use common\components\helpers\StringHelper;

Yii::$app->language = 'ru-RU';
?>

<div class="item">
    <div class="content <?php if (!$model->getBehavior('galleryBehavior')->getImages()): ?>no-image<?php endif; ?>">
        <div class="item__settings">
                <!--<a href="<? /*= $model->getUrl() */ ?>#comments"><? /*=$model->comments*/ ?></a><sub><i class="ic ic-comments"></i></sub>-->

            <span class="pull-right">
                <?= $model->views ?><sub><i class="ic ic-eye-open"></i></sub>
                <?= Yii::$app->formatter->asDatetime($model->time_create, 'd.MM.yy, hh:mm') ?>
            </span>
        </div>
        <?php if ($model->getBehavior('galleryBehavior')->getImages()): ?>
            <img
                    alt=""
                    class="item__image"
                    src="<?php foreach ($model->getBehavior('galleryBehavior')->getImages([0]) as $image) {
                        echo $image->getUrl('i600x328');
                    } ?>"
            >
        <?php endif; ?>
        <div class="item__info">
            <a title="<?= $model->user->getFullName() ?>" href="?userId=<?= $model->user->id ?>">
                <b>Автор:</b> <?= $model->user->getFullName() ?>
            </a>

            <a href="<?= $model->getUrl() ?>" data-pjax="0">
                <h3 class="item__title">
                    <?= $model->name ?>
                </h3>
            </a>

            <p class="item__desc">
                <?= StringHelper::truncate($model->small_desc, 250) ?>
            </p>
        </div>
    </div>
</div>





