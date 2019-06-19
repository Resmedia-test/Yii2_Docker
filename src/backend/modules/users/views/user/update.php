<?php

use yii\helpers\Html;
use common\models\User;
use yii\widgets\ActiveForm;
use zxbodya\yii2\imageAttachment\ImageAttachmentWidget;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->isNewRecord ? "Создание пользователя" : 'Обновление пользователя #' . $model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'user-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
<div class="modal-body">


    <div class="text-center">
        <?= ImageAttachmentWidget::widget([
            'id' => 'userImage',
            'model' => $model,
            'behaviorName' => 'coverBehavior',
            'apiRoute' => 'imgAttachApi',
        ]); ?>
    </div>
    <br/>
    <br/>
    <?= $form->field($model, 'role')->dropDownList(User::$roles)->label('Назначить новую роль?') ?>
    <?= $form->field($model, 'status')->dropDownList(User::$statuses) ?>
    <hr>
    <?= $form->field($model, 'email', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
    <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
    <?= $form->field($model, 'lastname', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
    <?= $form->field($model, 'password', ['options' => ['class' => 'form-group', 'value' => 'ПАРОЛЬ']])->passwordInput(['maxlength' => 250]) ?>
    <hr>
    <?= $form->field($model, 'phone', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
    <?= $form->field($model, 'about', ['options' => ['class' => 'form-group']])->textarea() ?>

</div>

<div class="modal-footer">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
