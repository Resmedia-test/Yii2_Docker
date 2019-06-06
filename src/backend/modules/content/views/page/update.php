<?php

use backend\widgets\MetaTags;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\Tabs;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание страницы" : 'Обновление страницы #'.$model->id;
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
                <?= $form->field($model, 'url')->textInput(['maxlength' => 250]) ?>

                <?= $form->field($model, 'title', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>

                <?= $form->field($model, 'content', ['options' => ['class' => 'form-group']])->widget(CKEditor::class, [
                    'id' => 'content',
                    'preset' => 'custom',
                    'kcfinder' => true,
                    'kcfOptions' => Yii::$app->params['kcfOptions'],
                    'clientOptions' => [
                        'allowedContent' => true,
                        'customConfig' => Yii::$app->params['urlToCke'],
                    ],

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

            <div id="meta" class="tab-pane fade in">
                <?=MetaTags::widget([
                        'model' => $model,
                        'form' => $form
                ]); ?>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
        modal_this = this;
        $(document).on('focusin.modal', function (e) {
            if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')
            ) {
                modal_this.$element.focus()
            }
        })
    };
    $("#modal").removeAttr("tabindex");
</script>
