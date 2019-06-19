<?php
use common\models\Subscription;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
$model = new Subscription();
$form = ActiveForm::begin([
    'action' => [
        '/subscription/guest'
    ],
    'options' => [
        'id' => 'subscription_form',
        'class' => 'subscription'
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]);
?>
    <script type="text/javascript">
        ;(function(){
            var $name = $('input[name="name"]');

            var methods = {
                init: function(){
                    $('#subscription_send').click(function () {
                        if (!$name.parent().hasClass('has-error')) {
                            submitForm();
                        }
                    });

                    this.checkName();
                },
                checkName: function(){
                    $name.focusout(function(){
                        if (!$(this).val().length) {
                            $(this).parent().removeClass('has-success').addClass('has-error');
                            $(this).parent().find('.help-block').text('Необходимо заполнить имя');
                        } else {
                            $(this).parent().removeClass('has-error').addClass('has-success');
                            $(this).parent().find('.help-block').text('');
                        }
                    });
                }
            };

            methods.init();
        })();
    </script>
    <h4>Отсталось еще немного</h4>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'name', ['inputOptions' => [
                    'placeholder' => 'Ваше имя',
                    'name' => 'name',
                    'class' => 'form-control',
                    'required'=>'required'
                ]])->textInput()->label(false) ?>
               
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <input type="checkbox" name="news" value="1" checked="checked" />
                <i class="ic ic-newspaper" ></i> НОВОСТИ-<span class="name" >ON!</span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <input type="checkbox" name="life" value="1" checked="checked" />
                <i class="ic ic-voice"></i>LIFE-<span class="name">СТИЛЬ</span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <input type="checkbox" name="articles" value="1" checked="checked" />
                <i class="ic ic-chronicle"></i>БЛОГ-<span class="name">POST</span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <input type="checkbox" name="direct" value="1" checked="checked" />
                <i class="ic ic-hazard"></i>ДИРЕКТ-<span class="name">WAY</span></a>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-default col-md-12" value="Подписаться" id="subscription_send">
    </div>

    <input type="hidden" name="email" value="<?php echo Html::encode($email); ?>" />
<?php ActiveForm::end(); ?>

<script>
    $('#modal').find('.modal-dialog').data('class', $('#modal').find('.modal-dialog').attr('class'));
    $('#modal').find('.modal-dialog').attr('class', 'modal-dialog modal-sm');
</script>
