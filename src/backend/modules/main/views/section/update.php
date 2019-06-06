<?php

use backend\widgets\MetaTags;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\Tabs;
use kartik\widgets\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use common\models\Section;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание раздела" : 'Обновление раздела #'.$model->id;
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'pages-form',
    'enableAjaxValidation' => true,
]); ?>
    <div class="modal-body">
        <?=Tabs::widget([
            'options' => ['class' => 'nav-tabs'],
            'encodeLabels' => false,
            'items' => [
                ['label' => 'Информация', 'options' => ['id' => 'info']],
                ['label' => 'Мета', 'options' => ['id' => 'meta']],
            ],
            'renderTabContent' => false,
        ]);?>
        <br>
        <div class="tab-content">
            <div id="info" class="tab-pane fade in active">
                <?= $form->field($model, 'module', ['options' => ['class' => 'form-group']])->dropDownList(Section::listModules(), ['prompt' => '']) ?>

                <?= $form->field($model, 'controller', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>

                <?= $form->field($model, 'action', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>

                <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>

                <?= $form->field($model, 'url', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>

                <?= $form->field($model, 'status')->widget(SwitchInput::class, [
                    'pluginOptions' => [
                        'size' => 'medium',
                        'onColor' => 'success',
                        'onText' => 'Да',
                        'offText' => 'Нет',
                    ]
                ]); ?>
            </div>

            <div id="meta" class="tab-pane fade in">
                <?=MetaTags::widget([
                    'model' => $model,
                    'form' => $form
                ]); ?>
            </div>
        </div>


    </div>

    <div class="modal-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>