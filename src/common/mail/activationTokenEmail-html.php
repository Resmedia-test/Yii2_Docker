<?php

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activation-email', 'token' => $user->activation_token]);
/**
 * @var $resetLink \common\models\User
 * @var $user \common\models\User
 */
?>

<h4>Здравствуйте, <?= Html::encode($user->name) ?>!</h4>
<p>Для активации Вашего email адреса перейдите по следующей ссылке: <br/>
<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
<p>
    <em>
        В случае, если Вы не производили никаких действий, просто проигнорируйте это письмо, но имейте
        ввиду, что Ваш Email использовался для регистрации. Более подробную информацию Вы можете узнать на сайте,
        сообщив об этом в отдел технической поддержки или ответив на это письмо.
    </em>
</p>

