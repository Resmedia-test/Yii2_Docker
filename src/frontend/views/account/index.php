<?php

use common\models\User;
use kartik\editable\Editable;
use kartik\popover\PopoverX;
use yii\helpers\Html;
use zxbodya\yii2\imageAttachment\ImageAttachmentWidget;

$this->title = 'Мой кабинет';

/** @var $pass \common\models\User */
/** @var $subscription \common\models\Subscription */

?>
<div class="cabinet container">
    <h1 class="name">Мой кабинет</h1>
    <br>

    <div class="col-lg-3 col-md-3">
        <div class="subscribe">
            <h3 class="sub-main-name">Подписан</h3>

            <div class="col-md-12 check-name">
                <label>
                    <?= Html::activeCheckbox($subscription, 'articles', [
                        'label' => false,
                        'onchange' => 'subscribe("articles", this.checked)',
                        'disabled' => empty(Yii::$app->user->model->email) || Yii::$app->user->status == User::STATUS_EMAIL_NC,
                    ]) ?>
                </label>
                <i class="ic ic-chronicle"></i>БЛОГ-<span class="name">POST</span>
            </div>

        </div>
        <br/><br/>
        <h3 class="sub-main-name">Фотография</h3>
        <div class="row cab-block ">
            <?= ImageAttachmentWidget::widget([
                'id' => 'userImage',
                'model' => $model,
                'behaviorName' => 'coverBehavior',
                'apiRoute' => '/account/imgAttachApi',
            ]); ?>
        </div>
    </div>

    <div class="col-lg-9 col-md-9">
        <h3 class="sub-main-name">Настройки</h3>

        <div class="form-group cab-block col-md-12">

            <div class="col-sm-4"><label class="control-label">Имя</label></div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'name',
                    'asPopover' => true,
                    'header' => 'Имя',
                    'size' => 'md',
                    'options' => ['class' => 'form-control', 'placeholder' => 'Введите Ваше имя...'],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                ]) ?>
            </div>

        </div>

        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4"><label class="control-label">Фамилия</label></div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'lastname',
                    'asPopover' => true,
                    'header' => 'Имя',
                    'size' => 'md',
                    'options' => ['class' => 'form-control', 'placeholder' => 'Введите Ваше имя...'],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                ]) ?>
            </div>
        </div>

        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4">
                <label class="control-label">Email
                    <?php if (Yii::$app->user->status == User::STATUS_EMAIL_NC): ?>
                        <i title="Ваш Email не активирован, функциональность ограничена." class="ic question red ic-question-sign"></i>
                    <?php else: ?>
                        <i title="При смене Email у вас будет отключена возможность писать комментарии и статьи, до момента его активации "
                           class="ic questiic ic-question-sign"></i>
                    <?php endif; ?>
                </label>
            </div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'email',
                    'asPopover' => true,
                    'header' => 'Email',
                    'size' => 'md',
                    'options' => ['class' => 'form-control', 'placeholder' => 'Введите email...'],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                    'pluginEvents' => ['editableSuccess' => 'function(){ window.location.reload(); }'],
                ]) ?>
            </div>
        </div>

        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4">
                <label class="control-label">Телефон</label>
            </div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'phone',
                    'asPopover' => true,
                    'header' => 'Phone',
                    'size' => 'md',
                    'options' => ['class' => 'form-control', 'placeholder' => '+79000000000'],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                ]) ?>
            </div>
        </div>

        <?php if (!empty($model->email)): ?>
            <div class="form-group cab-block col-md-12">
                <div class="col-sm-4"><label class="control-label">Пароль</label></div>

                <div class="col-sm-8">
                    <?= Editable::widget([
                        'model' => $pass,
                        'attribute' => 'passwordOld',
                        'inputType' => Editable::INPUT_PASSWORD,
                        'valueIfNull' => 'Изменить',
                        'displayValue' => 'Изменить',
                        'asPopover' => true,
                        'beforeInput' => function ($form, $widget) {
                            echo $form->field($widget->model, 'password')
                                ->passwordInput(['placeholder' => 'Введите новый пароль...'])
                                ->label(false);
                            echo '<br>';
                        },
                        'header' => 'Пароль',
                        'size' => 'md',
                        'options' => ['class' => 'form-control', 'placeholder' => 'Введите старый пароль'],
                        'resetButton' => ['style' => 'display:none'],
                        'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                    ]); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4"><label class="control-label">Пол</label></div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'gender',
                    'displayValue' => ($model->gender == User::GENDER_UNSET) ? '(не определен)' : ($model->gender ? 'Мужской' : 'Женский'),
                    'inputType' => Editable::INPUT_SWITCH,
                    'asPopover' => true,
                    'header' => 'Пол',
                    'size' => 'md',
                    'options' => [
                        'pluginOptions' => [
                            'size' => 'small',
                            'onColor' => 'default',
                            'offColor' => 'default',
                            'onText' => 'Мужской',
                            'offText' => 'Женский',
                            'labelText' => 'Пол',
                        ],
                        'tristate' => true,
                        'indeterminateValue' => User::GENDER_UNSET,
                        'indeterminateToggle' => ['label' => ''],
                    ],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                ]) ?>
            </div>
        </div>

        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4">
                <label class="control-label">Дата рождения</label>
            </div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'birthday',
                    'displayValue' => $model->birthday ? Yii::$app->formatter->asDate($model->birthday) : '(не задано)',
                    'inputType' => Editable::INPUT_DATE,
                    'asPopover' => true,
                    'header' => 'Дата рождения',
                    'size' => 'md',
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],
                    'options'=>[
                        'options'=>[
                                'value'=> $model->birthday ? Yii::$app->formatter->asDate($model->birthday) : '']
                    ]
                ]) ?>
            </div>
        </div>


        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4"><label class="control-label">О себе</label></div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'about',
                    'displayValue' => 'Нажмите, что-бы увидеть текст',
                    'inputType' => Editable::INPUT_TEXTAREA,
                    'placement' => PopoverX::ALIGN_TOP,
                    'submitOnEnter' => false,
                    'asPopover' => true,
                    'header' => 'О себе',
                    'size' => 'lg',
                    'options' => ['class' => 'form-control', 'rows' => 5, 'cols' => '100%', 'placeholder' => 'Этот текст будет отображён на Вашей личной странице'],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],

                ]) ?>
            </div>
        </div>

        <div class="form-group cab-block col-md-12">
            <div class="col-sm-4"><label class="control-label">Опыт</label></div>

            <div class="col-sm-8">
                <?= Editable::widget([
                    'model' => $model,
                    'attribute' => 'experience',
                    'displayValue' => 'Нажмите, что-бы увидеть текст',
                    'inputType' => Editable::INPUT_TEXTAREA,
                    'placement' => PopoverX::ALIGN_TOP,
                    'submitOnEnter' => false,
                    'asPopover' => true,
                    'header' => 'Опыт',
                    'size' => 'lg',
                    'options' => ['class' => 'form-control', 'rows' => 5, 'cols' => '100%', 'placeholder' => 'Этот текст будет отображён на Вашей личной странице'],
                    'resetButton' => ['style' => 'display:none'],
                    'submitButton' => ['icon' => '<i class="ic ic-ok-circle"></i>', 'class' => ''],

                ]) ?>
            </div>
        </div>

    </div>
    <?php if (!Yii::$app->user->model->email): ?>
        <div class="col-md-12 info">Для того, чтобы были доступны подписки на новости и новые сообщения введите свой
            email
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->user->status == 1): ?>
        <div class="col-md-12  info">Для того, чтобы были доступны подписки на новости и новые сообщения активируйте
            свой
            email. В случае если Вы не получили сообщение с сылкой активации, отправьте запрос с указанного email на
            адрес
            support@man-on.info с просьбой восстановления доступа.
        </div>
    <?php endif; ?>

</div>