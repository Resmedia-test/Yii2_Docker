<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Опубликовать авторский материал в интернет издании ON!';
$this->registerMetaTag(['name' => 'keywords', 'content' => 'публикация статьи, опубликовать статью, бесплатно опубликовать статью, публикация статьи']);
$this->registerMetaTag(['name' => 'description', 'content' => 'Публикация статей в интернет издании ON!']);
$this->registerMetaTag(['name' => 'og:title', 'content' => htmlspecialchars('Опубликовать авторский материал в интернет издании ON!')]);
$this->registerMetaTag(['name' => 'og:description', 'content' => htmlspecialchars('Публикация статей в интернет издании ON!')]);
$this->registerMetaTag(['name' => 'og:url', 'content' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']]);

$this->params['breadcrumbs'][] = ['label' => 'публикация статьи'];
?>


<div class="col-lg-1">

</div>
<div class="col-lg-8">
    <div class="col-lg-12 page">
        <?php $form = ActiveForm::begin([
            'id' => 'requestArticle-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
        ]); ?>

        <?= $form->field($model,'ip')->hiddenInput(['value'=>Yii::$app->getRequest()->getUserIP()])->label(false) ?>

        <p class="text">
            Для того, чтобы опубликовать статью, Вам необязательно регистрироваться.
            Вы можете отправить ее через эту форму. Если статья будет
            соответствовать <a class="" target="_blank" href="/pravila">правилам</a>, ее опубликуют
            наши редакторы.
        </p>

        <div class="block-border">
            <div class="name-block">Форма отправки статьи</div>
            <div class="form-group col-xs-12">
                <label for="exampleInputFile">Ваше фото</label>
                <!--<span class="btn btn-default btn-xs">Загрузить фото</span>-->
                <?= $form->field($model, 'file', ['options' => ['class' => 'form-group']])
                    ->fileInput()
                    ->label(false)
                    ->hint('Фото размерами не более 2 мб. Поддерживаемые форматы JPG PNG JPEG') ?>
            </div>

            <div class="form-group col-xs-6">
                <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])
                    ->label(false)
                    ->textInput(['maxlength' => 250, 'placeholder' => 'ФИО']); ?>
            </div>

            <div class="form-group col-xs-6">
                <?= $form->field($model, 'email', ['options' => ['class' => 'form-group']])
                    ->label(false)
                    ->textInput(['maxlength' => 250, 'placeholder' => 'Введите Ваш Email']); ?>
            </div>


            <div class="form-group col-xs-12">
                <?= $form->field($model, 'about', ['options' => ['class' => 'form-group']])
                    ->textArea(['maxlength' => 250, 'placeholder' => 'Введите информацию о себе'])
                    ->label(false)
                    ->hint('Длинна текста не более 250 знаков') ?>
                <hr/>
            </div>

            <div class="form-group col-xs-12">
                <?= $form->field($model, 'title', ['options' => ['class' => 'form-group']])
                    ->textInput(['maxlength' => 70, 'placeholder' => 'Введите название статьи'])
                    ->label(false)
                    ->hint('Длинна текста не более 70 знаков') ?>
            </div>

            <div class="form-group col-xs-12">
                <?= $form->field($model, 'anons', ['options' => ['class' => 'form-group']])
                    ->textArea(['maxlength' => 200, 'placeholder' => 'Введите анонс статьи'])
                    ->label(false)
                    ->hint('Длинна текста не более 200 знаков') ?>
            </div>

            <div class="form-group col-xs-12">

                <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])->widget(vova07\imperavi\Widget::class, [
                    'options' => [
                        'id' => 'requestArticle',
                    ],
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 200,
                        'pastePlainText' => true,
                        'buttonSource' => true,
                        'toolbarFixed' => false,
                        'placeholder' => 'Введите сам текст',
                        'buttons' => ['bold', 'italic', 'deleted', 'underline', 'link'],
                        'autocomplete'=>'off',
                    ],
                ])->label(false)
                    ->hint('Загрузка файлов и фото доступна зарегистрированным пользователям с возможностью написания статей');
                ?>

            </div>

            <div class="col-xs-12">
                <div class="checkbox text">
                    <label>
                        <?= $form->field($model, 'agree')
                            ->checkbox()
                            ->label(false); ?>
                    </label>
                    Я согласен с <a class="" target="_blank" href="/pravila">правилами</a>
                    интернет издания ON!. Статья отправляемая мной не нарушает <a class="" target="_blank" href="/pravila">
                        правила</a> интернет издания ON!
                </div>
            </div>
            <br>
            <div class="col-md-12 text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="col-lg-2">
    <div class="row">
        <?= \frontend\widgets\ArticleList::widget([]) ?>
    </div>
</div>
<div class="col-lg-1">

</div>
<script>
    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-lg');
</script>


