<?php
use common\models\Setting;

$url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
$copy = Setting::findOne(['code' => 'copy', 'hidden' => 0]);
$phone = Setting::findOne(['code' => 'phone', 'hidden' => 0]);
$address = Setting::findOne(['code' => 'address', 'hidden' => 0]);
$email = Setting::findOne(['code' => 'email', 'hidden' => 0]);
$main_name = Setting::findOne(['code' => 'main_name', 'hidden' => 0]);
$main_desc = Setting::findOne(['code' => 'main_desc', 'hidden' => 0]);
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
        <div style="width: 33.33333%;float: left;">
            <a title="FaceBook" href="<?= $url ?><?= $url ?>/facebook">
                <img
                    style="float: right;margin-right: 15px;width: 12%;"
                    src="<?= $url ?>/img/fb.png"
                    alt=""
                >
            </a>
            <a title="Вконтакте" href="<?= $url ?><?= $url ?>/vkontakte">
                <img
                    style="float: right;margin-right: 15px;width: 12%;"
                    src="<?= $url ?>/img/vk.png"
                    alt=""
                >
            </a>
            <a title="Twitter" href="<?= $url ?><?= $url ?>/twitter">
                <img
                    style="float: right;margin-right: 15px;width: 12%;"
                    src="<?= $url ?>/img/tw.png"
                    alt=""
                >
            </a>
        </div>
    </div>

    <div id="body" style="padding: 25px">
        <h4>Здравствуйте!</h4>
        <style scoped>
            a {
                color: #fa5c17;
                text-decoration: none;
            }

            a:hover {
                color: #666666;
            }
        </style>
        <p><strong>Имя:</strong>
            <?= $model->name ?></p>
        <p><strong>Email:</strong>
            <?= $model->email ?></p>
        <p><strong>Телефон:</strong>
            <?= $model->phone ?></p>
        <p><strong>Текст сообщения:</strong>
            <?= $model->text ?></p>
        <p><strong>Дата отправки:</strong>
            <?= Yii::$app->formatter->asTime($model->time_create, 'dd MMMM YYYY года в HH:mm') ?></p>

        <p><strong>Информация об отправителе: ip</strong> -
            <?= $model->ip ?> - TRUE <?= Yii::$app->getRequest()->getUserIP() ?></p>
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