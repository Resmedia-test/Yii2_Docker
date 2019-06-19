<?php

use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание сообщения" : 'Обновление сообщения #'.$model->id;
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'message-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'user_id')->widget(Select2::class, [
            'data' => User::listAll(),
            'options'        => [
                'id' => 'user_id',
            ],
        ]) ?>

        <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])->widget(CKEditor::class, [
            'id' => 'content',
            'preset' => 'custom',
            'clientOptions' => [
                'allowedContent' => true,
                'customConfig' => Yii::$app->request->BaseUrl.'/scripts/config-cke.js',
            ],
        ]); ?>

        <?= $form->field($model, 'ip')->textInput() ?>

        <?= $form->field($model, 'status', ['options' => ['class' => 'form_group']])
            ->widget(SwitchInput::class, [
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

<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
        modal_this = this;
        $(document).on('focusin.modal', function (e) {
            if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                modal_this.$element.focus()
            }
        })
    };
</script>