<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $task \common\models\TaskArticle
 * @var $subscription \common\models\Subscription
 */

?>

<h4>Здравствуйте, <?= Html::encode($subscription->getName()) ?>!</h4>

<?= $task->text ?>
<?= $html ?>

<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
    Данное письмо сгенерировано автоматически. Вы получили его на адрес <?= $subscription->getEmail() ?>
    в следствии подписки на материалы сайта
    <br/><br/>
    Для того, чтобы отписаться от рассылки, перейдите по
    <a href="<?= Url::to([
        'subscription/unsubscribe',
        'id' => $subscription->id,
        'email' => $subscription->getEmail()
    ], true) ?>"
    >
        ссылке
    </a>
</p>


