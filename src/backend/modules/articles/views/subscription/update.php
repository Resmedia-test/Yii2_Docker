<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Добавление подписчика" : 'Обновление подписчика #' . $model->id;
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'newsSubscription-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="modal-body col-md-12">
        <div class="row">
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'articles')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'size' => 'medium',
                    'onColor' => 'success',
                    'onText' => 'Да',
                    'offText' => 'Нет',
                ]
            ]); ?>
        </div>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>