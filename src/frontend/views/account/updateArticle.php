<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->isNewRecord ? "Создание статьи" : 'Обновление статьи #'.$model->id;
$this->registerJsFile('/js/toastr.min.js',  ['position' => View::POS_END]);
$this->registerCssFile('/css/toastr.min.css',  ['position' => View::POS_HEAD])
?>

    <h4><?= Html::encode($this->title) ?></h4>


<?php $form = ActiveForm::begin([
    'id' => 'article-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="modal-body">

        <i title="Название статьи на странице. Любое" class="ic ic-question-sign"></i>
        <?= $form->field($model, 'name', ['inputOptions' => ['class' => 'meta-name-count form-control']])
            ->textInput(['maxlength' => 150])
            ->hint('<p>Осталось <span class="text-name">150</span> символов</p>')
        ?>

        <i title="Описание статьи. Выводится как анонс под серой полосой. Не должно быть частью текста!" class="ic ic-question-sign"></i>
        <?= $form->field($model, 'small_desc', ['inputOptions' => ['class' => 'meta-desc-count form-control']])
            ->textarea(['maxlength' => 250])
            ->hint('<p>Осталось <span class="text-desc">250</span> символов</p>')
        ?>

        <i title="Ваша статья" class="ic ic-question-sign"></i>
        <?= $form->field($model, 'full_desc', ['options' => ['class' => 'form-group']])->widget(Widget::class, [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 300,
                'pastePlainText' => true,
                'buttonSource' => true,
                'toolbarFixed' => false,
                'plugins' => [
                    //'clips',
                    'fontcolor',
                    //'fontfamily',
                    //'fontsize',
                    //'fullscreen'
                ],
                'buttons' => [
                    'bold', 'italic', 'deleted', 'underline', 'horizontalrule', 'alignment', 'unorderedlist',
                    'orderedlist', 'outdent', 'indent', 'link', 'image', 'file',
                    ],
                'imageUpload' => Url::to(['/account/image-upload']),
                'imageManagerJson' => Url::to(['/account/images-get']),
                'fileManagerJson' => Url::to(['/account/files-get']),
                'fileUpload' => Url::to(['/account/file-upload']),
                'imageUploadErrorCallback' => new jsExpression(
                        'json => { 
                                          toastr["error"](json.error)
                                    }'
                ),

            ]
        ]); ?>

    </div>

    <div class="modal-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-default' : 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>

<script>

    $(document).ready(function(){

        var $title = $('.meta-title-count');
        var $length_title = $title.val().length;
        $title.parent().find('p').find('.text-title').text(80-$length_title);
        $title.bind('input propertychange', function(){
            if($(this).val().length > 80)
            {
                $(this).val( $(this).val().substring(0, 80) );
            }

            $length_title = $(this).val().length;
            $(this).parent().find('p').find('.text-title').text(80-$length_title);
        });

        var $name = $('.meta-name-count');
        var $length = $name.val().length;
        $name.parent().find('p').find('.text-name').text(150-$length);
        $name.bind('input propertychange', function(){
            if($(this).val().length > 150)
            {
                $(this).val( $(this).val().substring(0, 150) );
            }

            $length = $(this).val().length;
            $(this).parent().find('p').find('.text-name').text(150-$length);
        });

        var $desc = $('.meta-desc-count');
        var $length_desc = $desc.val().length;
        $desc.parent().find('p').find('.text-desc').text(250-$length_desc);
        $desc.bind('input propertychange', function(){
            if($(this).val().length > 250)
            {
                $(this).val( $(this).val().substring(0, 250) );
            }

            $length_desc = $(this).val().length;
            $(this).parent().find('p').find('.text-desc').text(250-$length_desc);
        });

    });

    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-lg');
</script>
