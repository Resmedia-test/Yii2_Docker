<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var $section \common\models\Section */
/** @var $model \common\models\Book */
/** @var $dataProvider \common\models\Book */
$this->params['breadcrumbs'][] = $section->name;
?>
<?php Pjax::begin(['id' => 'refresh', 'scrollTo' => 0]) ?>
<section class="handbook-items">
    <div class="container">
        <h1><?= $section->title; ?></h1>

        <div id="items-search">
            <div
                    class="text-center-line"
                    role="button"
                    data-toggle="collapse"
                    href="#search-items"
                    aria-expanded="false"
                    aria-controls="collapseExample"
            >
                Поиск по разделу
            </div>

            <div class="collapse" id="search-items">
                <div class="block">
                    <?php $form = ActiveForm::begin([
                        'id' => 'book-form',
                        'method' => 'GET',
                        'action' => '/' . $section->url,
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => false,
                    ]); ?>

                    <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])
                        ->textInput(['maxlength' => 250, 'placeholder' => 'Поиск по разделу ' . $section->name . ''])
                        ->label(false)
                    ?>

                    <div class="text-center">
                        <input type="submit" value="Искать" class="btn btn-default col-md-12">
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <aside>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_row',
                'pager' => [
                    'options' => ['class' => 'pages'],
                    'prevPageLabel' => '<i class="on on-chevron-left"></i>',
                    'nextPageLabel' => '<i class="on on-chevron-right"></i>',
                    'firstPageLabel' => '<i class="on on-step-backward"></i>',
                    'lastPageLabel' => '<i class="on on-step-forward"></i>',
                ],
                'summary' => false,
            ]); ?>
        </aside>
    </div>
</section>
<?php Pjax::end(); ?>
