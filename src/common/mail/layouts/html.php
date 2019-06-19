<?php

use yii\helpers\Html;
use common\models\Setting;

$url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
$copy = Setting::findOne(['code' => 'copy', 'status' => 1]);
$phone = Setting::findOne(['code' => 'phone', 'status' => 1]);
$address = Setting::findOne(['code' => 'address', 'status' => 1]);
$email = Setting::findOne(['code' => 'email_feedback', 'status' => 1]);
$main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
$main_desc = Setting::findOne(['code' => 'site_desc', 'status' => 1]);

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML PUBLIC>
<html lang="RU" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        a {
            color: #fa5c17;
            text-decoration: none;
        }

        a:hover {
            color: #666666;
        }
    </style>
</head>
<body>
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
                    src="<?= $url ?>/img/logo.png"
                    alt=""
            >
        </div>
        <div style="width: 33.33333%;float: left;">
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
        <?php $this->beginBody() ?>
        <?= $content ?>
        <?php $this->endBody() ?>
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
    </div>

</div>
</body>
</html>
<?php $this->endPage() ?>
