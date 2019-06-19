<?php

use yii\bootstrap\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activation', 'token' => $user->activation_token]);

?>


<h4>Здравствуйте, <?= Html::encode($user->name) ?>!</h4>
<style scoped>
    a {
        color: #fa5c17;
        text-decoration: none;
    }

    a:hover {
        color: #666666;
    }
</style>
<p>Ваш пароль от кабинета: <strong><?= $user->password ?></strong></p>
<p>Убедительно рекомендуем сразу сменить его в личном кабинете</p>
<p><em style="color:#ff6700">В случае, если Вы не производили никаких действий на сайте ON!, срочно свяжитесь с
        Технической Поддержкой проекта, ответив на это письмо.</em></p>




