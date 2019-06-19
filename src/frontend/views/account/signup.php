<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
?>

    <h4><i class="name ic ic-nameplate"></i> Регистрация</h4>


<?php $form = ActiveForm::begin([
    'id' => 'signup-form',
    'enableAjaxValidation' => true,
   // 'enableClientValidation' => false,
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name', ['inputOptions' => [
            'placeholder' => 'Имя',
            'class' => 'form-control',
        ]])->label(false) ?>

        <?= $form->field($model, 'lastname', ['inputOptions' => [
            'placeholder' => 'Фамилия',
            'class' => 'form-control',
        ]])->label(false) ?>

        <?= $form->field($model, 'email', ['inputOptions' => [
            'placeholder' => 'E-mail',
            'class' => 'form-control',
        ]])->label(false) ?>


        <div class="password-block">
        <?= $form->field($model, 'password', ['inputOptions' => [
            'placeholder' => 'Пароль',
            'class' => 'form-control password password1',
        ]])->passwordInput()->label(false) ?>
        </div>

        <?= $form->field($model, 'passwordRepeat', ['inputOptions' => [
            'placeholder' => 'Повторите пароль',
            'class' => 'form-control password2',
        ]])->passwordInput()->label(false) ?>


        <?= Html::submitButton('Регистрация', [
            'class' => 'btn btn-default col-md-12 col-sm-12 col-xs-12',
            'data-loading-text' => 'Сохраняем...',
        ]) ?>
    </div>
    <div class="modal-footer">
        <a class=" modalForm center-text" href="/login">Вход</a>
    </div>

<?php ActiveForm::end(); ?>
<script type="text/javascript" src="/js/pass.js"></script>
<script type="text/javascript">
    $(function() {
        $('.password').pstrength();
    });

    $(function() {
        $('#user-password').password().on('show.bs.password', function(e) {
            $('#methods').prop('checked', true);
        }).on('hide.bs.password', function(e) {
            $('#methods').prop('checked', false);
        });
        $('#methods').click(function() {
            $('#user-password').password('toggle');
        });
        $("#user-passwordRepeat").bind('paste', function() { return false;});

    });

    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-sm');
</script>
