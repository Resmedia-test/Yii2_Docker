<?php
use app\components\MistakeWidget;
use app\components\SocWidget;
use frontend\widgets\LastCommentsList;
use frontend\widgets\PopularComments;
use frontend\widgets\PopularArticles;
use frontend\widgets\LibraryWidget;

$this->params['breadcrumbs'][] = ['url' => '/' . $section->url, 'label' => $section->title];

if(isset($model->parent))
    $this->params['breadcrumbs'][] = ['url' => $model->parent->getUrl(), 'label' => $model->parent->name];

$this->params['breadcrumbs'][] = 'Материал';
?>

<div class="col-lg-1">

</div>

<div class="col-lg-8 col-md-9 page">
    <div class="col-md-12 mobile-row">
        <h1 class="section-name"><?= $model->name ?></h1>

        <h2 class="quote-item"><span id="quote-color"></span><?= $model->small_desc ?></h2>
        <?php if ($model->getBehavior('coverBehavior')->hasImage()): ?>
            <div class="img-page-yes">
                <img class="library-page-img" alt="" src="<?= $model->getCover('i600x328') ?>">
                <?= SocWidget::widget(); ?>
            </div>
        <?php else: ?>
            <div class="img-page-no">
                <?= SocWidget::widget(); ?>
            </div>
        <?php endif; ?>

        <article>
            <div class="text">
                <?= $model->full_desc ?>
            </div>
            <span class="date pull-right">
                    <i class="on on-history"></i>
                Опубликовано <?= Yii::$app->formatter->asDateTime($model->time_create) ?>
                </span>
        </article>


        <? if ($model->children): ?>
            <div class="lable-1">Приложения к подразделу:</div>
        <? endif; ?>

        <?php foreach ($model->children as $child): ?>
            <a class="child-link-library" href="<?= $child->getUrl() ?>"><i
                    class="on on-paperclip"></i> <?= $child->name ?></a>
        <?php endforeach; ?>
    </div>
</div>
<div class="col-lg-2 col-sm-3">
    <?= \frontend\widgets\LibraryWidget::widget([]) ?>
</div>

<div class="col-lg-1">

</div>
