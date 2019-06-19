<?php
/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 12.05.19
 * Time: 13:11
 */
/** @var $article \common\models\Article */
?>

<aside class="feed">
    <div class="feed__name">
        Публикации
    </div>
    <?php foreach ($article as $model): ?>
        <div class="feed__item">
            <div class="feed__header">
                <img
                        class="feed__user-img"
                        alt="<?= $model->user->getFullName() ?>"
                        src="<?= $model->user->getPreview() ?>"
                >
                <div class="feed__user-name">
                    <?= $model->user->getFullName() ?>
                </div>
            </div>
            <div class="feed__body">
                <a href="<?= $model->getUrl() ?>" data-pjax="0">
                    <?= $model->name ?>
                </a>
            </div>
            <div class="feed__info">
                <span class="feed__info-item">
                    <i class="ic ic-clock"></i>
                    <?= Yii::$app->formatter->asDatetime($model->time_create, 'dd.MM, HH:mm') ?>
                </span>
                <span class="feed__info-item">
                    <a href="#">
                        <?= $model->commentsCount ?><sub><i class="ic ic-comments"></i></sub>
                    </a>
                    <?= $model->views ?>
                    <sub>
                        <i class="ic ic-eye-open"></i>
                    </sub>
                </span>
            </div>
        </div>
    <?php endforeach; ?>
</aside>

    

