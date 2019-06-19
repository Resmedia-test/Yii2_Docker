<?php

use yii\helpers\Html;

/**
 * @var $resetLink \common\models\User
 * @var $user \common\models\User
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activation', 'token' => $user->activation_token]);
?>

<h4>Здравствуйте, <?= Html::encode($user->name) ?>!</h4>
<p>Для активации и получения пароля перейдите по следующей ссылке: <br/>
<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
<p>
    <em>
        В случае, если Вы не производили никаких действий на сайте ON!, просто проигнорируйте это письмо, но
        имейте ввиду, что Ваш Email использовался для регистрации. Более подробную информацию Вы можете узнать
        на сайте, сообщив об этом в техподдержку или ответив на это письмо.
    </em>
</p>
