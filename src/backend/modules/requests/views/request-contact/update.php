<?php

use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/** @var $model common\models\RequestContact */
$this->title = $model->isNewRecord ? "Создание запроса" : 'Обновление запроса #'.$model->id;
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'reqeustContact-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="modal-body">

        <?= $form->field($model, 'ip', ['options' => ['class' => 'form-group']])
            ->textInput(['maxlength' => 250]); ?>
        
        <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])
            ->textInput(['maxlength' => 250]); ?>

        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
            'mask' => ['+9(999) 999-99-99']
        ]); ?>

        <?= $form->field($model, 'email', ['options' => ['class' => 'form-group']])
            ->textInput(['maxlength' => 250]); ?>

        <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])
            ->textArea(); ?>


        <?= $form->field($model, 'status')->widget(SwitchInput::class, [
            'pluginOptions' => [
                'size' => 'meduim',
                'onColor' => 'success',
                'onText' => 'Обработан',
                'offText' => 'Не_обработан',
            ]
        ]); ?>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>