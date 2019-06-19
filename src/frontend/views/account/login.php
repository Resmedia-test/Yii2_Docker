<?php

use frontend\widgets\AuthChoice;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

?>

<h4><i class="name ic ic-fingerprint-scan"></i> Вход</h4>


<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    //'enableClientValidation' => false,
]); ?>
<ul class="auth-clients">
    <?=AuthChoice::widget([
        'baseAuthUrl' => ['account/auth'],
        'popupMode' => false,
    ]) ?>
</ul>

<br>
    <div class="modal-body">
        <?= $form->field($model, 'username', ['inputOptions' => [
            'placeholder' => 'E-mail',
            'class' => 'form-control',
        ]])->textInput()->label(false) ?>

        <div class="password-block">
        <?= $form->field($model, 'password', ['inputOptions' => [
            'placeholder' => 'Пароль',
            'class' => 'form-control',
            'id'=>'password',
        ]])->passwordInput()->label(false) ?>
        </div>

        <?= Html::submitButton('Вход', [
            'class' => 'btn btn-default col-md-12 col-sm-12 col-xs-12',
            'data-loading-text' => 'Подождите...',
        ]) ?>
    </div>

    <div class="modal-footer">
        <a class="modalForm btn btn-default col-md-12 col-sm-12 col-xs-12" href="/signup">Регистрация</a><br/><br>
        <a class="modalForm recovery text-center" href="/recovery">Забыли пароль?</a>
    </div>

<?php ActiveForm::end(); ?>

<script>
    $(function() {
        $('#password').password().on('show.bs.password', function(e) {
            $('#methods').prop('checked', true);
        }).on('hide.bs.password', function(e) {
            $('#methods').prop('checked', false);
        });
        $('#methods').click(function() {
            $('#password').password('toggle');
        });
    });

    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-sm');
</script>
