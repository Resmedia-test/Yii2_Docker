<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;


?>
<div class="info-404-text col-md-12">
    <div class="col-md-1">
        <div class="castle">
            <div class="switch expandUp lock"></div>
        </div>
    </div>
    <div class="col-md-4">
        <p class="error-title"><?= Html::encode($this->title) ?></p>
    </div>
    <div class="col-md-7">
        Сожалеем, но у Вас нет доступа к этой части портала! Войдите или зарегистрируйтесь. В случае, если Вы уверены, что такого не должно быть, обратитесь в ТехПоддержку.
    </div>
</div>

<div class="page-error text-center">

    <a href="/" class="text-center btn btn-default">Перейти на главную страницу портала</a>
</div>


<div id="img_wrapper">
    <div id="error">

    </div>
</div>

<script>
    function lock() {
        $(".switch").addClass("lock2");
    }
    setTimeout(lock, 2000);
</script>