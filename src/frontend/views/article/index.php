<?php


use common\components\helpers\MonthHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var $year \common\models\Article */
/** @var $day \common\models\Article */
/** @var $month \common\models\Article */
/** @var $dataProvider \common\models\Article */
/** @var $user \common\models\Article */

$this->params['breadcrumbs'][] = ['label' => 'Публикации', 'url' => '/articles'];

$metaTitle = 'Публикации';

if(isset($year)){
    $this->params['breadcrumbs'][] = [
        'label' => $year,
        'url' => $month ? '/articles/' . $year : null,
    ];
    !$month ? $metaTitle = 'Публикации за ' . $year . ' год' : null;
}

if(isset($month)){
    $this->params['breadcrumbs'][] = [
        'label' => $month,
        'url' => $day ? '/articles/' . $year . '/' . $month : null,
    ];
    !$day ? $metaTitle = 'Публикации за ' . MonthHelper::toWords($month) : null;
}

if(isset($day)){
    $this->params['breadcrumbs'][] = [
        'label' => $day,
        'url' => null,
    ];
    $metaTitle = 'Публикации за ' . $day . ' число';
}

if($metaTitle){
    $this->title = $metaTitle;
    Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $metaTitle], 'description');
    Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => 'test'], 'keywords');
}
?>

<div class="container">
    <?php Pjax::begin(['id' => 'refresh', 'scrollTo' => 0]) ?>

    <h1><?= $metaTitle ?></h1>
    <?php if($user) {?>
       <h2>Пользователь <?= $user->getFullName() ?></h2>
    <?php } ?>
    <form class="form-inline" method="GET" action="<?=Url::to('')?>">
        <div class="form-group col-md-5">
            <div class="row">
                <?= DatePicker::widget([
                    'name' => 'create_from',
                    'value' => $create_from,
                    'options' => ['placeholder' => 'Дата от'],
                ])?>
            </div>
        </div>
        <div class="form-group col-md-offset-2 col-md-5">
            <div class="row">
                <?= DatePicker::widget([
                    'name' => 'create_to',
                    'value' => $create_to,
                    'options' => ['placeholder' => 'Дата до'],
                ])?>
            </div>
        </div>
        <br>
        <br>

        <input type="text" class="form-control" id="" name="name" value="<?=$name?>" placeholder="Введите запрос">
        <br>
        <br>
        <div class="text-center">
            <input type="submit" value="Искать" class="btn btn-default col-md-12">
        </div>
    </form>

    <br>
    <div class="article-all-items">
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

    <?php Pjax::end(); ?>
</div>