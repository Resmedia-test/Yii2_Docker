
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
        
    </div>
    <div class="col-md-4">
    <p class="error-title"><?= Html::encode($this->title) ?></p>
</div>
<div class="col-md-7">
    Сожалеем, но что-то пошло не так! <?= nl2br(Html::encode($message)) ?>.. <br> Хорошие новости заключаются в том, что мы уже об этом уведомлены и сделаем все возможное, чтобы Вы больше на нее не попадали.

</div>
  </div>
<div class="page-error text-center">
    
    <a href="/" class="text-center btn btn-default">Перейти на главную страницу портала</a>
</div>


<div id="img_wrapper">
    <div id="error">
        
    </div>
</div>




