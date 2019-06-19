<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $user common\models\User
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);

?>

<h4>Здравствуйте, <?= Html::encode($user->name) ?>!</h4>

<p>
    Перейдите по ссылке, чтобы сбросить пароль: <?= $resetLink ?>
</p>
<p>
    <em>
        В случае если Вы не делали, каких либо действий,
        просто проигнорируйте это письмо, но имейте в виду, что кто-то
        пытался воспользоваться Вашим email.
    </em>
</p>

<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
    Данноесообщение отправлено автоматически на адрес <?= $user->email ?>
    так как Ваш email использован для регистрации
</p>


