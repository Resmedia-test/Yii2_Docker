<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = $model->isNewRecord ? "Создание меню" : 'Обновление меню #'.$model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'menu-form',
    'enableAjaxValidation' => true,
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 500]) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 550]) ?>

        <?= $form->field($model, 'levels')->widget(SwitchInput::class, [
            'pluginOptions' => [
                'size' => 'medium',
                'onColor' => 'success',
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]); ?>

        <?= $form->field($model, 'status')->widget(SwitchInput::class, [
            'pluginOptions' => [
                'size' => 'medium',
                'onColor' => 'success',
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]); ?>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>


