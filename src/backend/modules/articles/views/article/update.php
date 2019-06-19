<?php

use backend\widgets\GalleryManager;
use backend\widgets\MetaTags;
use common\models\User;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\Tabs;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание страницы" : 'Обновление страницы #' . $model->id;
Html::encode($this->title);
?>
<script async src="<?= Yii::$app->params['urlToSyncTranslit'] ?>"></script>
<?php if ($model->isNewRecord): ?>
    <script>
        $(document).ready(function () {
            $('#object-name').syncTranslit({destination: 'article-url'});
        });
    </script>
<?php else: ?>
    <script>
        $('#open-url').on('click', function () {
            var message = confirm("Вы уверены, что хотите отредактировать это? Возможны негативные последствия в индексации!");
            if (message === true) {
                $('#article-url').removeAttr('disabled');
                $(document).ready(function () {
                    $('#article-name').syncTranslit({destination: 'article-url'});
                });
                $('#open-url').setAttribute('disabled');
            }
        });
    </script>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'pages-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false
]); ?>
<div class="modal-body">
    <?= Tabs::widget([
        'options' => ['class' => 'nav-tabs'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Информация', 'options' => ['id' => 'info']],
            ['label' => 'Мета', 'options' => ['id' => 'meta']],
        ],
        'renderTabContent' => false,
    ]); ?>
    <br>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">

            <?= $form->field($model, 'time_create', ['options' => ['class' => 'form_group']])
                ->widget(DateTimePicker::class, [
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy hh:ii',
                    ],
                ]) ?>

            <i title="Автор материала" class="glyphicon glyphicon-question-sign"></i>

            <?= $form->field($model, 'user_id')->widget(Select2::class, [
                'data' => User::listAll(),
                'options' => [
                    'id' => 'user_id',
                ],
            ]) ?>

            <?php echo $model->id > 0 ? GalleryManager::widget([
                'model' => $model,
                'behaviorName' => 'galleryBehavior',
                'apiRoute' => 'galleryApi'
            ]) : '<i title="Так как материалу не присвоен ID галлерею нельзя загрузить" class="glyphicon glyphicon-question-sign"></i> <b>Фото загружается после создания материала</b><br><br>'; ?>

            <i title="Название материала на странице" class="glyphicon glyphicon-question-sign"></i>
            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?php if (!$model->isNewRecord): ?>
                <div class="input-group">
                    <?= $form->field($model, 'url')->textInput([$model->isNewRecord ? '' : 'disabled' => 'disabled'])->label(false) ?>
                    <span class="input-group-btn">
                        <button id="open-url" class="btn btn-default" type="button">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </button>
                    </span>
                </div>
            <?php else: ?>
                <?= $form->field($model, 'url')->textInput()->label(false) ?>
            <?php endif; ?>
            <br/>
            <i title="Анонс, небольшое описание выводится на общих страницах и над материалом. Не должно быть частью текста!"
               class="glyphicon glyphicon-question-sign"></i>
            <?= $form->field($model, 'small_desc', ['options' => ['class' => 'form-group']])->textArea(['maxlength' => 250, 'rows' => 3])->hint('Не более 250 символов') ?>

            <i title="Сам материал, визуально может отличаться от того, что будет на странице"
               class="glyphicon glyphicon-question-sign"></i>
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

        </div>

        <div id="meta" class="tab-pane fade in">
            <?= MetaTags::widget([
                'model' => $model,
                'form' => $form
            ]); ?>
        </div>
    </div>
</div>

<div class="modal-footer">

    <?= $form->field($model, 'status', ['options' => ['class' => 'form_group col-md-3']])
        ->widget(SwitchInput::class, [
            'pluginOptions' => [
                'size' => 'medium',
                'onColor' => 'success',
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]); ?>

    <?= $form->field($model, 'article_main', ['options' => ['class' => 'form_group col-md-3']])
        ->widget(SwitchInput::class, [
            'pluginOptions' => [
                'size' => 'medium',
                'onColor' => 'success',
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'views', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn btn-success pull-right']) ?>
    </div>

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
</script>
