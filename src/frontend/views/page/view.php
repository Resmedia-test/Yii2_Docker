<?php

$this->title = $model->getBehavior('MetaTag')->title;
?>

<?php
$this->title = $model->title;
$this->params['breadcrumbs'][] = 'Страница';
?>

<section class="page">
    <article class="container">
        <h1><?= $model->title ?></h1>
        <?= $model->content ?>
    </article>
</section>

