<?php

use yii\helpers\Url;

/**
 * @var $model \common\models\Comment
 */

?>

<h4>Здравствуйте!</h4>

<p>
    Пользователь <?= $model->user->getFullName() ?> <?= Yii::$app->formatter->asDatetime($model->time_create, 'dd.MM.YY в HH:mm') ?>
    подал жалобу на комментарий: </p>
<em><?= $model->comment->text ?></em>
<p>
    <a href="<?= Url::to($model->comment->getUrl(), true) ?>">перейти к нему</a>
</p>

