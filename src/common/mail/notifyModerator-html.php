<h4>Здравствуйте!</h4>

<p>
    <strong>Имя:</strong>
    <?= $model->name ?>
</p>
<p>
    <strong>Email:</strong>
    <?= $model->email ?>
</p>
<p>
    <strong>Телефон:</strong>
    <?= $model->phone ?>
</p>
<p>
    <strong>Текст сообщения:</strong>
    <?= $model->text ?>
</p>
<p>
    <strong>Дата отправки:</strong>
    <?= Yii::$app->formatter->asTime($model->time_create, 'dd MMMM YYYY года в HH:mm') ?>
</p>

<p>
    <strong>Информация об отправителе: ip</strong> -
    <?= $model->ip ?> - TRUE <?= Yii::$app->getRequest()->getUserIP() ?>
</p>
