<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = "Связаться"
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'requestContact-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
<div class="modal-body">
    <?= $form->field($model,'ip')->hiddenInput(['value'=>Yii::$app->getRequest()->getUserIP()])->label(false) ?>

    <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])
        ->textInput(['maxlength' => 250, 'placeholder' => 'Как к Вам обращаться'])->label(false) ?>

    <?= $form->field($model, 'phone', ['options' => ['class' => 'form-group']])->widget(MaskedInput::class, [
        'options' => ['id' => 'phone1'],
        'mask' => ['+9(999) 999-99-99'],
    ])->textInput(['placeholder' => 'Ваш номер телефона', 'id' => 'phone1'])->label(false); ?>

    <?= $form->field($model, 'email', ['options' => ['class' => 'form-group']])
        ->textInput(['maxlength' => 250, 'placeholder' => 'Введите Ваш Email'] )->label(false); ?>

    <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])
        ->textArea(['placeholder' => 'Введите текст сообщения'])->label(false) ?>
</div>

<div class="modal-footer">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-default']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-md');
</script>
