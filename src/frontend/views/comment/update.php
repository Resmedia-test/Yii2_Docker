<?php
/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 08.08.16
 * Time: 20:39
 */

use common\models\User;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use v0lume\yii2\metaTags\MetaTags;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\components\Tabs;
use kartik\widgets\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use common\models\Article;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = 'Обновление комментария';
?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'message-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="modal-body">

        <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])->widget(vova07\imperavi\Widget::class, [
            'options' => [
                'id' => 'commentUpdate',
            ],
            'settings' => [
                'lang' => 'ru',
                'linkNofollow' => true,
                'linkSize' => 20,
                'minHeight' => 100,
                'pastePlainText' => true,
                'buttonSource' => true,
                'toolbarFixed' => false,
                'buttons' => ['bold', 'italic', 'deleted', 'underline', 'link'],
                'autocomplete'=>'off',
            ],
        ])->label(false); ?>

    </div>

    <div class="modal-footer">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-default col-md-12']) ?>
    </div>

<?php ActiveForm::end(); ?>

<script>
    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-md');
</script>
