<?php

use yii\helpers\Url;

$this->title = htmlspecialchars($model->getFullName() . ' пользователь сайта');

/*$this->registerMetaTag(['name' => 'keywords', 'content' => htmlspecialchars('Автор статей '.$model->getFullName() )]);
$this->registerMetaTag(['name' => 'description', 'content' => htmlspecialchars($model->about)]);
$this->registerMetaTag(['name' => 'og:title', 'content' => htmlspecialchars('Автор статей '.$model->getFullName())]);
$this->registerMetaTag(['name' => 'og:description', 'content' => htmlspecialchars($model->about)]);
$this->registerMetaTag(['name' => 'og:url', 'content' => 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' .$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']]);*/

$this->params['breadcrumbs'][] = ['label' => 'все пользователи', 'url' => '/users'];

$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => null];

?>


<section class="container">
    <img class="user-face" alt="" src="<?= $model->getPreview('i200x200') ?>">
    <h1 class="user-name">
        <?= $model->getFullName() ?>
    </h1>

    <div class="btn-group" role="group">
        <?php if ($model->articlesCount): ?>
            <a
                    class="btn btn-default"
                    href="<?= $model->articlesCount ? Url::to(['/article', 'userId' => $model->id]) : '#' ?>"
                    data-pjax="0">
                <?= Yii::t('app', '{n, plural, =0{Нет статей} one{# Статья} other{# Статьи} many{# Статей}}', ['n' => $model->articlesCount]) ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ($model->birthday): ?>
        <div class="user-birthday">
            <b>Дата рождения:</b>
            <?= Yii::$app->formatter->asDate($model->birthday, 'dd MMMM') ?>
        </div>
    <?php endif; ?>

    <div class="user-about">
        <b>О себе:</b>
        <?= $model->about ?>
    </div>

</section>

