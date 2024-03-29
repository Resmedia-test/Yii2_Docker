<?php

use common\models\Setting;
use yii\helpers\Html;
use common\components\helpers\StringHelper;
$site_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

$this->title = "Внимание!"
?>

<h4 class="name"><i class="ic ic-exclamation-sign"></i> <?= Html::encode($this->title) ?></h4>

<div class="modal-body">
    <p>
        Вы нажали на ссылку с <b>URL: <?=StringHelper::truncate($url, 30)?></b> оставленную в комментарии одним из пользователей ресурса.
    </p>
    <p>
      <?= $site_name->value ?> не несет какой либо ответсвенности за ресурс <?=StringHelper::truncate($url, 20)?>. Вы переходите по ссылке полностью на свой страх и риск.
    </p>
    <p class="text-center">
        Ссылка будет открыта в новой вкладке.<br>
        В случае если Вы передумали, то нажмите "отмена"
    </p>
</div>

<div class="modal-footer">
    <a class="btn btn-default redirLink col-md-5" href="<?=$url?>">Открыть ссылку</a>
    <button type="button" class="btn btn-default pull-right col-md-5" data-dismiss="modal">Отмена</button>
</div>

<script>
    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-md');

    let loc = "<?php echo $url; ?>";

    $("a.redirLink").on("click",function(){
        let win = window.open(loc, '_blank');
        win.focus(loc);
        $('#modal').modal('hide');
        return false;
    });
</script>
