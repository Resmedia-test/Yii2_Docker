<?php
use backend\assets\AppAsset;
use common\models\Setting;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name .' '. $main_name->value ?: 'Название сайта',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-static-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);

    $menuItems = [
        [
            'label' => 'Вернуться на сайт',
            'url' => '/',
        ],
        [
            'label' => 'Выход (' . Yii::$app->user->identity->name . ')',
            'url' => ['/main/main/logout'],
        ],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= $content ?>
    </div>


    <?php Pjax::begin(['id' => 'refreshModal']); ?>
    <?php
    Modal::begin([
        'id' => 'modal',
        'options' => ['data-backdrop' => 'static'],
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
    ?>
    <?php Pjax::end(); ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
