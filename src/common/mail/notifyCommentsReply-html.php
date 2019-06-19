<?php

use yii\helpers\Url;

/** @var $subscription \common\models\SubscriptionComment */
/** @var $model \common\models\SubscriptionComment */
?>

<h4>Здравствуйте, <?= $subscription->user->getFullName() ?>!</h4>

<h3>Уведомление об ответе на ваш комментарий</h3>

<p>
    К вашему комментарию в
    <a href="<?= Url::to($model->getUrl(), true) ?>" title="перейти в публикацию">
        <strong>публикации</strong>
    </a> добавлен новый ответ.
</p>

<p>Пользователь: <?= $model->user->getFullName() ?></p>
<p>Время: <?= Yii::$app->formatter->asDatetime($model->time_create) ?></p>
<p><?= $model->text ?></p>

<p>Обращаем Ваше внимание в виду возможных вариантов подписки их может быть уже гораздо больше.</p>
<p>Мы стараемся, чтобы Вы были в курсе всех новых событий.</p>
<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
    Данноесообщение отправлено автоматически наадрес <?= $model->user->email ?>
    так как Вы подписаны на уведомления об ответах.
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
