<?php

use backend\widgets\MetaTags;
use common\components\Tabs;
use yii\helpers\Html;
use common\models\User;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->isNewRecord ? "Создание пользователя" : 'Обновление пользователя #'.$model->id;
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'user-form',
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
                <?= $form->field($model, 'role')->dropDownList(User::$roles) ?>
                <?= $form->field($model, 'roleName', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
                <?= $form->field($model, 'status')->dropDownList(User::$statuses) ?>
                <hr>
                <?= $form->field($model, 'email', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
                <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
                <?= $form->field($model, 'password', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
                <?= $form->field($model, 'passwordRepeat', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250]) ?>
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
