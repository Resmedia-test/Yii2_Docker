<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание настройки" : 'Обновление настройки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'settings-form',
    'enableAjaxValidation' => true,
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

        <?= ($model->isNewRecord) ? $form->field($model, 'code')->textInput(['maxlength' => 50]) : $form->field($model, 'code')->textInput(['id' => 'disabledInput', 'disabled' => 'disabled']) ?>

        <?php if ($model->isNewRecord): ?>
            <h4>Перед созданием настройки определите тип поля</h4>
        <?php else: ?>

            <?php if ($model->getElement() == 'text'): ?>

                <?= $form->field($model, 'value')->textInput(); ?>

            <?php elseif ($model->getElement() == 'editor'): ?>

                <?= $form->field($model, 'full_desc', ['options' => ['class' => 'form-group']])
                    ->widget(CKEditor::class, [
                    'id' => 'content',
                    'preset' => 'custom',
                    'kcfinder' => true,
                    'kcfOptions' => Yii::$app->params['kcfOptions'],
                    'clientOptions' => [
                        'allowedContent' => true,
                        'customConfig' => Yii::$app->params['urlToCke'],
                    ],

                ]); ?>

            <?php else: ?>

                <?= $form->field($model, 'value')->textarea(['rows' => 6]); ?>

            <?php endif; ?>
        <?php endif; ?>


        <?= ($model->isNewRecord) ?
            $form->field($model, 'element')
                ->dropDownList([
                    'text' => 'Text',
                    'textarea' => 'Textarea',
                    'editor' => 'Editor',
                    ]) : '' ?>

        <?= $form->field($model, 'status')->widget(SwitchInput::class, [
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