<?php

use common\models\Comment;
use frontend\widgets\AuthChoice;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

?>

<h4>Быстрая регистрация</h4>


<?php $form = ActiveForm::begin([
    'id' => 'login-guest-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>

    <div class="modal-body">
        <?= $form->field($model, 'email', ['inputOptions' => [
            'placeholder' => 'E-mail',
            'class' => 'form-control',
        ]])->textInput()->label(false) ?>

        <?= $form->field($model, 'name', ['inputOptions' => [
            'placeholder' => 'Имя',
            'class' => 'form-control',
        ]])->textInput()->label(false) ?>

        <?= Html::submitButton('Регистрация', [
            'class' => 'btn btn-default btn-loading col-md-12 col-sm-12 col-xs-12',
            'data-loading-text' => 'Подождите...',
        ]) ?>
    </div>

    <div class="modal-footer">
        <ul class="auth-clients">
            <?=AuthChoice::widget([
                'baseAuthUrl' => ['account/auth'],
                'popupMode' => false,
            ]) ?>
        </ul>

        <a class="modalForm recovery text-center" href="/login">Уже зарегистрированы?</a>
    </div>

<?php ActiveForm::end(); ?>

<script>
    let guestComment = $('#comment-form-guest');
    let modal = $('#modal');
    if (guestComment.length) {
        let comment = {
            text: guestComment.find('textarea[name="Comment[text]"]').val(),
            model: guestComment.find('input[name="Comment[model]"]').val(),
            model_id: guestComment.find('input[name="Comment[model_id]"]').val(),
            reply_id: guestComment.find('input[name="Comment[reply_id]"]').val()
        };

        Cookies.set('<?= Comment::COOKIE_GUEST_VAR?>', Base64.encode(JSON.stringify(comment)));
    }

    modal.find('.modal-dialog').data('class', modal.find('.modal-dialog').attr('class'));
    modal.find('.modal-dialog').attr('class', 'modal-dialog modal-sm');
</script>
