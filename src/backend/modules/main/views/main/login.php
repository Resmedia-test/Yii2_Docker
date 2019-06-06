<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{input}\n{error}",
                ],
            ]); ?>
                <?= $form->field($model, 'username', ['inputOptions' => [
                    'placeholder' => $model->getAttributeLabel('username'),
                ]]) ?>
                <?= $form->field($model, 'password', ['inputOptions' => [
                    'placeholder' => $model->getAttributeLabel('password'),
                ]])->passwordInput() ?>
                <div class="form-group text-center">
                    <?= Html::submitButton('Enter', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
