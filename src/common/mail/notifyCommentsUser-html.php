<?php

use yii\helpers\Url;

/** @var $subscription \common\models\SubscriptionComment */

?>

<h4>Здравствуйте, <?= $model->user->getFullName() ?>!</h4>

<p>Пользователь: <?= $model->user->getFullName() ?></p>
<p>Время: <?= Yii::$app->formatter->asDatetime($model->time_create) ?></p>
<p><?= $model->text ?></p>
<p><a href="<?= Url::to($model->getUrl(), true) ?>">перейти</a></p>

<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
    Данное сообщение отправлено автоматически на адрес <?= $model->user->email ?>
    так как Вы подписаны на новые комментарии к материалу.
</p>
<p>
    Чтобы отписаться, перейдите по
    <a href="<?= Url::to([
        '/comment/unsubscribe',
        'id' => $subscription->id,
        'user_id' => $model->user->id
    ], true) ?>"
    >
        ссылке
    </a>
</p>

