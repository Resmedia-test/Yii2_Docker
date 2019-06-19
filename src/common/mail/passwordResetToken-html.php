<?php

use yii\bootstrap\Html;

/**
 * @var $resetLink $user \common\models\User
 * @var $user \common\models\User
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>

<h4>Здравствуйте, <?= Html::encode($user->name) ?>!</h4>

<p>Вы указали свой email при восстановлении пароля на сайте ON!</p>
<p> Для получения нового пароля перейдите по этой
    <a href="<?= $resetLink ?>" title="Новый пароль">
        ссылке
    </a>
</p>
<p>
    <em>В случае если Вы не делали, каких либо действий на сайте ON!,
        просто проигнорируйте это письмо, но имейте в виду, что кто-то
        пытался воспользоваться Вашим email
    </em>
</p>

<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
    Данное сообщение отправлено автоматически на адрес <?= $user->email ?>
    так как Ваш email был использован для регистрации
</p>
