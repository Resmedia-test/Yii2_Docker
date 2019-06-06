<?php

use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

$menuItems = [
    [
        'label' => '<i class="glyphicon glyphicon-book"></i><span class="sm-hidden">Библиотека</span>',
        'encode' => false,
        'url' => '/library',
        'active' => Yii::$app->controller->module->id == "library",
        'visible' => Yii::$app->user->can('backend.library'),
    ],
    [
        'label' => '<i class="glyphicon glyphicon-file"></i><span class="sm-hidden">Контент</span>',
        'encode' => false,
        'url' => '/content',
        'active' => Yii::$app->controller->module->id == "content" && Yii::$app->controller->id !== "order",
        'visible' => Yii::$app->user->can('backend.content'),
    ],
    [
        'label' => '<i class="glyphicon glyphicon-user"></i><span class="sm-hidden">Пользователи</span>',
        'encode' => false,
        'url' => '/users',
        'active' => Yii::$app->controller->module->id == "users",
        'visible' => Yii::$app->user->can('backend.users'),
    ],
    [
        'label' => '<i class="glyphicon glyphicon-envelope"></i><span class="sm-hidden">Запросы</span>',
        'encode' => false,
        'url' => '/requests',
        'active' => Yii::$app->controller->module->id == "requests",
        'visible' => Yii::$app->user->can('backend.requests'),
    ],
];

?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>

<nav class="navbar navbar-inverse visible-xs">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Основное меню</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                echo Nav::widget([
                    'options' => ['class' => 'nav-pills nav-stacked'],
                    'items' => $menuItems,
                ]);
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="leftColumnCover hidden-xs">
    <div class="leftColumnContent sm-hidden">
        <div id="aside" class="columnSidebar">
            <?php
            echo Nav::widget([
                'options' => ['class' => 'nav-pills nav-stacked'],
                'items' => $menuItems,
            ]);
            ?>
        </div>
    </div>
</div>

<div id="article" class="rightColumnContent content-width">
    <?= Breadcrumbs::widget([
        'homeLink' => [
            'label' => Yii::$app->name,
            'url' => Yii::$app->homeUrl,
        ],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?php
    $menu = [];

    if (method_exists(Yii::$app->controller->module, 'menu'))
        foreach (Yii::$app->controller->module->menu() as $label => $url)
            $menu[] = [
                'label' => $label,
                'url' => is_array($url) ? reset($url) : $url,
                'active' => is_array($url) ? in_array(Url::to(), $url) : Url::to() == $url,
            ];

    if (!empty($menu) && !(Yii::$app->controller->id == 'main' && Yii::$app->controller->action->id == 'index')) {
        echo Nav::widget([
            'options' => ['class' => 'nav-tabs'],
            'items' => $menu,
        ]);
    }
    ?>
    <?= $content ?>
</div>
<?php $this->endContent(); ?>

<script>
    (function () {
        var a = document.querySelector('#aside'), b = null, P = 0;
        window.addEventListener('scroll', Ascroll, false);
        document.body.addEventListener('scroll', Ascroll, false);

        function Ascroll() {
            if (b === null) {
                var Sa = getComputedStyle(a, ''), s = '';
                /*for (var i = 0; i < Sa.length; i++) {
                    if (Sa[i].indexOf('overflow') == 0 || Sa[i].indexOf('padding') == 0 || Sa[i].indexOf('border') == 0 || Sa[i].indexOf('outline') == 0 || Sa[i].indexOf('box-shadow') == 0 || Sa[i].indexOf('background') == 0) {
                        s += Sa[i] + ': ' +Sa.getPropertyValue(Sa[i]) + '; '
                    }
                }*/
                b = document.createElement('div');
                b.style.cssText = s + ' box-sizing: border-box; width: ' + a.offsetWidth + 'px;';
                a.insertBefore(b, a.firstChild);
                var l = a.childNodes.length;
                for (var i = 1; i < l; i++) {
                    b.appendChild(a.childNodes[1]);
                }
                a.style.height = b.getBoundingClientRect().height + 'px';
                a.style.padding = '0';
                a.style.border = '0';
            }
            var Ra = a.getBoundingClientRect(),
                R = Math.round(Ra.top + b.getBoundingClientRect().height - document.querySelector('#article').getBoundingClientRect().bottom);
            if ((Ra.top - P) <= 0) {
                if ((Ra.top - P) <= R) {
                    b.className = 'stop';
                    b.style.top = -R + 'px';
                } else {
                    b.className = 'sticky';
                    b.style.top = P + 'px';
                }
            } else {
                b.className = '';
                b.style.top = '';
            }
            window.addEventListener('resize', function () {
                a.children[0].style.width = getComputedStyle(a, '').width
            }, false);
        }
    })()
</script>