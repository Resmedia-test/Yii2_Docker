
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
    К сожалению что-то пошло не так! <?= nl2br(Html::encode($message)) ?>. <br> Но мы уже об этом уведомлены и сделаем все возможное, чтобы Вы больше на нее не попадали.

</div>
  </div>
<div id="img_wrapper">
    <img id="gear" src="/img/404.svg" alt="gear" data-big="/img/404colour.svg" />
</div>


