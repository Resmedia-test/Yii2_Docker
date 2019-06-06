<?php

use app\components\MistakeWidget;
use common\models\Menu;
use common\models\Setting;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$site_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
$copy = Setting::findOne(['code' => 'copy', 'status' => 1]);
$rules = Setting::findOne(['code' => 'rules', 'status' => 1]);
$google = Setting::findOne(['code' => 'google', 'status' => 1]);
$yandex = Setting::findOne(['code' => 'yandex', 'status' => 1]);

if (isset(Yii::$app->controller->model)) {
    $this->title = Yii::$app->controller->model->getBehavior('MetaTag')->title;
}
?>

<?php $this->beginPage() ?>

    <html lang="ru"
          xmlns="http://www.w3.org/1999/xhtml"
          xmlns:og="http://ogp.me/ns#"
          itemscope itemtype="http://schema.org/WebPage"
    >

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <title><?= Html::encode($this->title) ?></title>
        <?php Yii::$app->metaTags->register(Yii::$app->controller->model); ?>
        <?= Html::csrfMetaTags() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <?php $this->head() ?>

        <meta name="author" content="TestSite">
        <meta property="og:img" content="<?= Url::to(Yii::$app->controller->image, true) ?>">
        <meta property="og:image" content="<?= Url::to(Yii::$app->controller->image, true) ?>">
        <meta property="og:site_name" content="TestSite">
        <meta property="og:type" content="website">

        <link rel="shortcut icon"
              href="<?= 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['SERVER_NAME']; ?>/img/favicon.ico"
              type="image/x-icon"
        />
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/img/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/img/apple-touch-icon-152x152.png">

        <link rel="stylesheet" href="/css/main.css?_v1">
    </head>

    <?php $this->beginBody() ?>
    <body>

    <header class="header">
        <nav class="navbar navbar-defaul">
            <div class="container">
                <div class="navbar-header">
                    <button
                            type="button"
                            class="navbar-toggle collapsed"
                            data-toggle="collapse"
                            data-target="#navbar"
                            aria-expanded="false"
                            aria-controls="navbar"
                    >
                        <span></span>
                    </button>

                    <a class="navbar-brand" href="/">
                        <?= $site_name->value ?: '' ?>
                    </a>
                </div>

                <div id="navbar" class="collapse navbar-collapse">
                    <?php
                    $menu = Menu::find()->where(['name' => 'main'])->one();
                    echo \yii\widgets\Menu::widget([
                        'options' => ['class' => 'nav navbar-nav'],
                        'items' => $menu->itemsArray,
                        'encodeLabels' => false,
                    ]);
                    ?>
                </div>
            </div>
        </nav>

        <div class="container">
            <form id="search-form-top" action="/search" method="GET" onsubmit="search()">
            <span class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Введите запрос">
                <span class="input-group-btn">
                    <?= Html::submitButton('Искать', ['class' => 'btn btn-default']) ?>
                </span>
            </span>
            </form>
            <div class="breadcrumbs" itemprop="breadcrumb">
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::$app->name,
                        'url' => Yii::$app->homeUrl,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </div>
        </div>
    </header>

    <?= Alert::widget() ?>
    <?= $content ?>

    <footer class="footer" itemscope itemtype="http://schema.org/WPFooter">
        <div class="container">
            <div class="footer__content">
                <div class="row" itemscope itemtype="http://www.schema.org/SiteNavigationElement">
                    <?php
                    $menu = Menu::find()->where(['name' => 'footer'])->one();
                    echo \yii\widgets\Menu::widget([
                        'options' => ['class' => 'col-md-8'],
                        'items' => $menu->itemsArray,
                        'encodeLabels' => false,
                    ]);
                    ?>
                </div>
            </div>

            <div class="copyright">
                <div class="row">
                    <div class="col-md-4">
                        <div class="hidden-xs">
                            <?= MistakeWidget::widget(); ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <?= @$copy->value ?: '' ?>
                            </div>
                            <div class="col-md-12 copyright__rules">
                                <?= @$rules->value ?: '' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php
    Modal::begin([
        'id' => 'modal',
        //'options' => ['data-backdrop' => 'static'],
        'size' => Yii::$app->controller->modalSize,
        'closeButton' => ['onclick' => '$(\'.modalForm.disabled\').removeClass(\'disabled\');']
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?= @$yandex->value ?: '' ?>
    <?= @$google->value ?: '' ?>
    <?php $this->endBody() ?>

    <script src="/js/main.js?5"></script>

    <!--TODO Make it normal component with listenEvent-->
    <script type="text/javascript" src="/js/mistake.js"></script>
    <script type="text/javascript">
        (function (e) {
            jQuery(document).textmistake({});
        })(jQuery)
    </script>
    </body>
    </html>

<?php $this->endPage() ?>