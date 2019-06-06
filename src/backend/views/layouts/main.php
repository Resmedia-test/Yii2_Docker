<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
<script>
    $(function () {
        $('#refresh').tooltip({
            selector: 'a, button, i'
        });
        $('body').tooltip({
            selector: 'i'
        });
    });
</script>

</head>
<body>
    <?php $this->beginBody() ?>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);

    $menuItems = [
        [
            'label' => '<span class="time" id="timeNow">
                     <script src="/office/scripts/time.js"></script>
             </span>',
            'url' => false,
            'options' => ['class' => 'hidden-sm hidden-xs'],
        ],
        [
            'label' => 'Вернуться на сайт',
            'url' => '/',
        ],
        [
            'label' => '<i class="glyphicon glyphicon-cog"></i> Настройки',
            'url' => ['/main/setting/index'],
            'visible' => Yii::$app->user->can('backend.main.setting'),
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

    <div class="content admin-body col-md-12">
        <div class="row">
            <?= $content ?>
        </div>
    </div>


    <?php Pjax::begin(['id' => 'refreshModal']); ?>

    <?php
    Modal::begin([
        'id' => 'modal',
        'options' => [
            'data-backdrop' => 'static',
            'tabindex' => false
        ],
        'size' => Yii::$app->controller->modalSize,
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
    ?>

   <?php Pjax::end();?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
