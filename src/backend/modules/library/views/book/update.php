<?php

use backend\widgets\MetaTags;
use common\models\Section;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\components\Tabs;
use kartik\widgets\SwitchInput;
use common\models\Book;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание книги" : 'Обновление книги #'.$model->id;
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'book-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
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
                <i title="Раздел в котором, должен находиться материал" class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'section_id', ['options' => ['class' => 'form-group']])->dropDownList(ArrayHelper::map(Section::find()->where(['module' => 'library'])->all(), 'id', 'name'), ['prompt' => '']) ?>

                <i title="Родительский элемен в разделе, при создании ссылка будет
                иметь вид /раздел/родитель/материал/ используется для создания книг,
                а также формирования больших материалов с постраничным выводом.
                В этом случае на родительской странице будут все ссылки на вложенные материалы." class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'parent_id', ['options' => ['class' => 'form-group']])->dropDownList(ArrayHelper::map(Book::find()->where(['not', ['id' => $model->id]])->all(), 'id', 'name'), ['prompt' => '']) ?>

                <i title="Название материала на странице" class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>

                <i title="URL страницы" class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'url', [
                    'options' => ['class' => 'form-group'],
                    'addon' => [
                        'prepend' => ['content' => '/url_раздела/'],
                    ]
                ])->textInput(['maxlength' => 250]) ?>

                <i title="Анонс, небольшое описание выводится на общих страницах и над материалом. Не должно быть частью текста!" class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'small_desc', ['options' => ['class' => 'form-group']])->textArea(['maxlength' => 250, 'rows' => 5]) ?>

                <i title="Сам материал, визуально может отличаться от того, что будет на странице" class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'full_desc', ['options' => ['class' => 'form-group']])->widget(CKEditor::class, [
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

                <?= $form->field($model, 'time_create', ['options' => ['class' => 'form-group']])
                    ->widget(DateTimePicker::class, [
                        'options' => [
                            'class' => 'form_field_text',
                        ],
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy hh:ii',
                            'pickerPosition' => 'top-right',
                        ],
                        'removeButton' => false,
                    ]) ?>
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
