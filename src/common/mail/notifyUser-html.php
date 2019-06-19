<h4>Здравствуйте, <?= $model->name ?>!</h4>

<h3>Вы отправляли:</h3>
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
<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">
    Данноесообщение отправлено автоматически наадрес <?= $model->email ?>
    так как Вы отправили запрос. Отвечать на это письмо не обязательно.
</p>