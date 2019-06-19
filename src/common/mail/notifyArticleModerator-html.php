<h4>Здравствуйте! Отправлена новая статья на модерацию.</h4>
<p>
    <strong>Имя:</strong>
    <?= $model->name ?>
</p>
<p>
    <strong>Краткое описание:</strong>
    <?= $model->small_desc ?>
</p>
<p>
    <strong>Полный текст:</strong>
    <?= $model->full_desc ?>
</p>
<p>
    <strong>Дата отправки:</strong>
    <?= Yii::$app->formatter->asTime($model->time_create, 'dd MMMM YYYY года в HH:mm') ?>
</p>

<p>
    <strong>Информация об отправителе: ip</strong> -
    <?= Yii::$app->getRequest()->getUserIP() ?>
</p>
