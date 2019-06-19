<?php

use frontend\widgets\ArticleList;
use frontend\widgets\CommentsForm;
use frontend\widgets\Rate;

/**
 * @var $month {string} \common\models\Article
 * @var $year {string}  \common\models\Article
 * @var $day {string}  \common\models\Article
 */

$this->title = empty($model->getBehavior('MetaTag')->title) ? $model->name : $model->getBehavior('MetaTag')->title;

$this->params['breadcrumbs'][] = ['label' => 'Публикации', 'url' => '/articles'];

if(isset($year)){
    $this->params['breadcrumbs'][] = [
        'label' => $year,
        'url' => $month ? '/articles/' . $year : null,
    ];
}

if(isset($month)){
    $this->params['breadcrumbs'][] = [
        'label' => $month,
        'url' => $day ? '/articles/' . $year . '/' . $month : null,
    ];
}

$this->params['breadcrumbs'][] = [
    'label' => $day,
    'url' => null,
];

/** @var  $model \common\models\Article*/

?>
<script>
    jQuery(document).ready(function ($) {
        $('.text img').each(function () {
            var $this = $(this);
            if ($this.attr('alt')) {
                $this.replaceWith(function () {
                    return '<div class="text-img" style="' + $this.attr('style') + '"><img src="' + $this.attr('src') + '"><div class="descr-img">' + $this.attr('alt') + '</div></div>';
                });
            }
        });
    });
</script>
<div class="container">
    <section class="page col-lg-8 col-sm-9" itemscope itemtype="http://schema.org/Article">

        <h1 itemscope itemprop="headline">
            <?= $model->name ?>
        </h1>

        <div class="page__content">
            <div class="page__head">
                <img class="page__user-img" src="<?= $model->user->getPreview() ?>">
                <div class="page__user-info">
                    <h4 itemprop="author publisher">
                        <b>Автор: </b><?= $model->user->getFullName() ?>
                    </h4>
                    <a
                            title="Публикации пользователя"
                            href="/articles?userId=<?= $model->user->id ?>"
                            class="page__user-items"
                    >
                        Все публикации автора
                    </a>
                    <p class="page__user-about">
                        <?= $model->user->about ?>
                    </p>
                    <span class="page__date">
                        <i class="ic ic-clock"></i>
                        <?= Yii::$app->formatter->asDate($model->time_create, 'dd.MM.YY в HH:mm') ?>
                        <meta itemprop="datePublished" content="<?= date('Y-m-d\TH:i:s', $model->time_create) ?>"/>
                    </span>
                </div>
            </div>

            <h2 class="page__quote" itemscope itemprop="description">
                <?= $model->small_desc ?>
            </h2>

            <div class="page__gallery" itemprop="associatedMedia">
                <div itemscope itemtype="http://schema.org/ImageObject">
                    <?php if ($model->getBehavior('galleryBehavior')->getImages()): ?>
                        <div class="page__gallery-iems"
                             data-width="100%"
                             data-ratio="3/2"
                             data-nav="thumbs"
                             data-allowfullscreen="true"
                             data-thumbheight="48"
                        >
                            <?php
                            foreach ($model->getBehavior('galleryBehavior')->getImages() as $image) {
                                echo '<a href="' . $image->getUrl('original') . '" data-caption="' . $image->name . '">';
                                echo '<img itemprop="contentUrl" alt="' . $image->name . '" src="' . $image->getUrl('preview') . '">';
                                echo '</a>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="page__body" itemprop="articleBody">
                <?= $model->full_desc ?>
            </div>

            <?= Rate::widget(['model_id' => $model->id]) ?>

        </div>

        <div class="page__comments">
            <?= CommentsForm::widget([
                'model_class' => 'Article',
                'model_id' => $model->id,
            ]) ?>
        </div>
    </section>

    <div class="col-lg-4 col-sm-3">
        <?= ArticleList::widget([]) ?>
    </div>
</div>



