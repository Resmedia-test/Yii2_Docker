<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>

<?php
use common\models\Setting;
use yii\bootstrap\Html;

$url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
$copy = Setting::findOne(['code' => 'copy', 'hidden' => 0]);
$phone = Setting::findOne(['code' => 'phone', 'hidden' => 0]);
$address = Setting::findOne(['code' => 'address', 'hidden' => 0]);
$email = Setting::findOne(['code' => 'email', 'hidden' => 0]);
$main_name = Setting::findOne(['code' => 'main_name', 'hidden' => 0]);
$main_desc = Setting::findOne(['code' => 'main_desc', 'hidden' => 0]);
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activation', 'token' => $user->activation_token]);

?>

<div style="
max-width: 1000px;
min-width:600px;
margin: 0 auto;
font-family:Helvetica, serif;
font-size:16px;
border: 1px solid;
border-color: rgba(153, 153, 153, 0.24);
color: rgb(51, 51, 51);
-webkit-box-shadow: 0 0 5px 0 rgba(50, 50, 50, 0.75);
-moz-box-shadow:    0 0 5px 0 rgba(50, 50, 50, 0.75);
box-shadow:         0 0 5px 0 rgba(50, 50, 50, 0.75);
">
    <div style="background: rgb(245, 245, 245);
		padding: 25px;
		position:relative;
		min-height: 40px;"
    >
        <div style="width: 33.33333%; float: left;">
            <span style="margin-top: 10px; font-size: 120%; display: block;">
                <?= @strip_tags($main_name->value ?: '') ?>
            </span>
        </div>

        <div style="width: 33.33333%;float: left;">
            <img
                style="    width: 70%; margin: -31px auto; display: block;"
                src="<?= $url ?>/img/ON.png"
                alt=""
            >
        </div>
        <div style="width: 33.33333%; float: left;">
            <a title="FaceBook" href="<?= $url ?>/facebook">
                <img
                    style="float: right;margin-right: 15px;width: 12%;"
                    src="<?= $url ?>/img/fb.png"
                    alt=""
                >
            </a>
            <a title="Вконтакте" href="<?= $url ?>/vkontakte">
                <img
                    style="float: right;margin-right: 15px;width: 12%;"
                    src="<?= $url ?>/img/vk.png"
                    alt=""
                >
            </a>
            <a title="Twitter" href="<?= $url ?>/twitter">
                <img
                    style="float: right;margin-right: 15px;width: 12%;"
                    src="<?= $url ?>/img/tw.png"
                    alt=""
                >
            </a>
        </div>
    </div>

    <div id="body" style="padding: 25px">
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
       <p>Перейдите по ссылке, чтобы сбросить пароль: <?= $resetLink ?></p>
        <p>
            <em>В случае если Вы не делали, каких либо действий на сайте ON!,
                просто проигнорируйте это письмо, но имейте в виду, что кто-то
                пытался воспользоваться Вашим email.
            </em>
        </p>
    </div>

    <div id="footer" style="background: #f5f5f5; font-size: 90%; padding: 25px; position:relative;min-height: 50px">

        <b style="font-weight:300; margin-bottom: 5px; display:block;">
            Адрес: <?= @strip_tags($address->value ?: '') ?>
        </b>
        <b style="font-weight:300; margin-bottom: 5px; display:block;">
            Телефон: <?= @$phone->value ?: '' ?>
        </b>
        <b style="font-weight:300; margin-bottom: 5px; display:block;">
            Email: <?= @$email->value ?: '' ?>
        </b>
        <p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 30px;">
            <?= @$copy->value ?: '' ?>
        </p>
        <p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
            Данноесообщение отправлено автоматически на адрес <?= $user->email ?>
            так как Ваш email использован для регистрации на сайте ON!
        </p>
    </div>
</div>

