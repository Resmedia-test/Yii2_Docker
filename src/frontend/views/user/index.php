<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/*$this->title = 'Пользователи';*/

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => null];


/*$this->registerMetaTag(['name' => 'title', 'content' => 'Пользователи']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Пользователи']);
$this->registerMetaTag(['name' => 'description', 'content' => 'Пользователи']);*/

$orderLastname = '?orderBy=lastname&orderDirection=1';
$orderArticlesCount = '?orderBy=acount&orderDirection=1';
$orderRating = '?orderBy=arate&orderDirection=1';

if (in_array(Yii::$app->request->get('orderBy', ''), ['lastname', 'acount', 'arate'])) {
    $orderBy = Yii::$app->request->get('orderBy', '');
    $orderDirection = (int)Yii::$app->request->get('orderDirection', 0);

    if ($orderBy == 'lastname') {
        if ($orderDirection == 1) {
            $orderLastname = '?orderBy=lastname&orderDirection=-1';
        }

        if ($orderDirection == -1) {
            $orderLastname = '';
        }
    }

    if ($orderBy == 'acount') {
        if ($orderDirection == 1) {
            $orderArticlesCount = '?orderBy=acount&orderDirection=-1';
        }

        if ($orderDirection == -1) {
            $orderArticlesCount = '';
        }
    }

    if ($orderBy == 'arate') {
        if ($orderDirection == 1) {
            $orderRating = '?orderBy=arate&orderDirection=-1';
        }

        if ($orderDirection == -1) {
            $orderRating = '';
        }
    }
}
?>

<div class="container">
    <h1 class="sectiontitle-name">
        Пользователи
    </h1>

    <?php Pjax::begin(['id' => 'refresh']) ?>
    <div class="user-search">
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'options' => ['data-pjax' => true],
        ]); ?>
        <div class="input-group">
            <?= Html::activeTextInput($filterModel, 'fullName', [
                'class' => 'form-control',
                'placeholder' => 'Введите Фамилию, имя или отчество'
            ]) ?>
            <span class="input-group-btn">
                <?= Html::submitButton('Искать', ['class' => 'btn btn-default']) ?>
            </span>
        </div>
        <br>
        <div class="sorting">
            Сортировать по:
            <a href="<?= Url::to(['']) . $orderLastname ?>" class="active-sort">ФИО</a>
            <a href="<?= Url::to(['']) . $orderArticlesCount ?>" class="active-sort">Количеству статей</a>
            <a href="<?= Url::to(['']) . $orderRating ?>" class="active-sort">По рейтингу</a>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


    <section id="users">
        <div class="row">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                //'layout' => '{items}<div id="paginition" class = "clearfix">{pager}',
                'itemView' => '_row',
                'pager' => [
                    'options' => ['class' => 'pages'],
                    'prevPageLabel' => '<i class="ic ic-chevron-left"></i>',
                    'nextPageLabel' => '<i class="ic ic-chevron-right"></i>',
                    'firstPageLabel' => '<i class="ic ic-step-backward"></i>',
                    'lastPageLabel' => '<i class="ic ic-step-forward"></i>',
                ],
                'summary' => false,
            ]); ?>
        </div>
    </section>

    <?php Pjax::end(); ?>
</div>

