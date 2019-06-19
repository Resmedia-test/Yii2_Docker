<?php
/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 15.05.16
 * Time: 13:11
 */
use yii\helpers\Url;

?>

<div class="all-feed hidden-xs">

    <div class="all-feed-name">
        <i class="ic ic-book"></i>ЗНАНИЕ - <span class="name">LIBRARY</span>
    </div>

    <div class="loading">
        <div id="block-loader">
            <span class="loader"></span>
        </div>

        <div class="simplebar-scroll-top" data-simplebar-direction="vertical">

            <div class="scroll-news">
                <?php foreach ($book as $model): ?>
                    <div class="item">
                        <div class="title">
                            <b>Раздел: <?= $model->section->name ?></b><br>
                            <a href="<?= $model->getUrl() ?>" data-pjax="0">
                                <?= \yii\helpers\StringHelper::truncate($model->name, 120) ?>&hellip;
                            </a>
                        </div>
                        <div class="news-feed-cvd">
                            <span
                                class="pull-right"><?= Yii::$app->formatter->asDatetime($model->time_create, 'dd.MM.YY, HH:mm') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>
