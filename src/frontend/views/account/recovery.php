<?php

use frontend\widgets\AuthChoice;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

?>

<h4>Восстановление пароля</h4>


<?php $form = ActiveForm::begin([
    'id' => 'user-form',
    'enableAjaxValidation' => true,
]); ?>

<div class="modal-body">
    <?= $form->field($model, 'email', ['inputOptions' => ['placeholder' => 'E-mail', 'class' => 'form-control']])->textInput()->label(false) ?>

    <?= Html::submitButton('Восстановить', [
        'class' => 'btn btn-default btn-loading col-md-12 col-sm-12 col-xs-12',
        'data-loading-text' => 'Подождите...',
    ]) ?>

    <div class="modal-footer">
        <div class="col-md-12">
            <br>
            <a class="modalForm" href="/login">Вход</a>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-sm');
</script>
