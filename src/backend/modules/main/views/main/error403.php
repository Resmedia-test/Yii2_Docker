<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;


?>
<div class="info-404-text col-md-12">
    <div class="col-md-3">
        <p class="error-title"><?= Html::encode($this->title) ?></p>
    </div>
    <div class="col-md-9">
        К сожалению у Вас нет доступа к этому разделу! Войдите или зарегистрируйтесь. В случае, если Вы уверены, что такого не должно быть, обратитесь в ТехПоддержку.
    </div>
</div>
<div class="col-md-12">
    <div class="castle">
        <div class="switch expandUp lock"></div>
    </div>

    <div class="">
        <?= nl2br(Html::encode($message)) ?>
    </div>
</div>

<script>
    function lock() {
        $(".switch").addClass("lock2");
    }
    setTimeout(lock, 2000);
</script>