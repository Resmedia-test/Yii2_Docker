<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use common\models\MenuLink;

/* @var $this yii\web\View */
/* @var $model common\models\MenuLink */

$this->title = $model->isNewRecord ? "Создание пункта меню" : 'Обновление пункта меню #'.$model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'menu-form',
    'enableAjaxValidation' => true,
]); ?>
    <div class="modal-body">
        <?php if($menu->levels): ?>
        <?= $form->field($model, 'parent_id')->dropDownList(MenuLink::getParents($model->menu_id, $model->id), ['prompt' => '']) ?>
        <?php endif; ?>

        <?= $form->field($model, 'title', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 550]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => 500]) ?>

        <?= $form->field($model, 'class')->textInput(['maxlength' => 250]) ?>

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