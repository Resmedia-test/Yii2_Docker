<?php

use yii\helpers\Url;

/**
 * @var $model \common\models\Comment
 */
?>

<h4>Здравствуйте!</h4>
<p>Пользователь: <?= $model->user->getFullName() ?></p>
<p>Время: <?= Yii::$app->formatter->asDatetime($model->time_create) ?></p>
<p><?= $model->text ?></p>
<p><a href="<?= Url::to($model->getUrl(), true) ?>">перейти</a></p>
