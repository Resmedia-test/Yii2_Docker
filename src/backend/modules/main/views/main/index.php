<?php
/* @var $this yii\web\View */

$url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
$this->title = Yii::$app->name;
?>
