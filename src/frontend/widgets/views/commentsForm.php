<?php
use common\models\Comment;
use common\models\SubscriptionComment;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>
<?php if (Yii::$app->user->status == 1): ?>
    <div class="info">
        Для комментирования Вам необходимо активировать свой email. В случае если Вы не
        получили сообщение с сылкой активации, отправьте запрос с указанного email на адрес support@man-on.info с
        просьбой восстановления доступа.
    </div>
<?php endif; ?>

<div class="comment-block col-md-12">
    <?php if (Yii::$app->user->isGuest || Yii::$app->user->can(Comment::RBAC_FRONTEND_CREATE, ['status' => Yii::$app->user->status], false)): ?>
        <div class="comment-form scrollTo">
            <?php $form = ActiveForm::begin([
                'id' => 'comment-form' . (Yii::$app->user->isGuest ? '-guest' : ''),
                'action' => ['/comment/create'],
                'method' => 'POST',
                'enableAjaxValidation' => !Yii::$app->user->isGuest,
                'enableClientValidation' => false,
            ]); ?>

            <div class="label label-warning errorForm" id="Сomment_text_em_" style="display:none"></div>
            <div class="updateBlock" style="display: none">
                Редактирование комментария #<a href=""></a> <a href="#cancelUpdate"><i class="ic ic-remove"></i></a>
            </div>

            <div class="replyBlock reply-info"></div>

            <?= $form->field($model, 'id', ['template' => "{input}"])->hiddenInput() ?>
            <?= $form->field($model, 'model', ['template' => "{input}"])->hiddenInput() ?>
            <?= $form->field($model, 'model_id', ['template' => "{input}"])->hiddenInput() ?>
            <?= $form->field($model, 'reply_id', ['template' => "{input}"])->hiddenInput() ?>

            <?= $form->field($model, 'text',
                ['template' => "{input}\n{error}", 'options' => ['class' => 'form-group']])
                ->widget(Widget::class, [
                'settings' => [
                    'lang' => 'ru',
                    'linkNofollow' => true,
                    'linkSize' => 20,
                    'minHeight' => 100,
                    'pastePlainText' => true,
                    'buttonSource' => true,
                    'toolbarFixed' => false,
                    'buttons' => ['bold', 'italic', 'deleted', 'underline', 'link'],
                    'autocomplete'=>'off',
                ],

            ]) ?>

            <div class="row">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <div class="col-md-4">

                        <?php if(Yii::$app->user->model->email):?>
                        <div class="dropdown">

                        <span id="subradio" type="button" data-toggle="dropdown" aria-haspopup="true"
                              aria-expanded="false">
                            <i class="ic ic-message-full"></i>
                        </span>

                            <i title="Подписка на новые комментарии к публикации"
                               class="on questiic ic-question-sign"></i>

                            <ul class="dropdown-menu comment-subscribe" aria-labelledby="subradio">

                                <?= Html::radioList(
                                    'subscription',
                                    (isset($subscription) ? $subscription->type_id : null),
                                    SubscriptionComment::$types,
                                    [
                                        'data-model' => $model_class,
                                        'data-model_id' => $model_id,
                                        'class' => 'subscriptionBlock',
                                        'disabled' => empty(Yii::$app->user->model->email),
                                    ]
                                ) ?>
                            </ul>

                        </div>
                        <?php else: ?>
                            <i title="Для того, чтобы получать уведомления, необходимо указать Email в личном кабинете" class="ic ic-message-full"></i>
                        <?php endif; ?>

                    </div>


                <?php endif; ?>

                <div class="col-md-8 <?= !Yii::$app->user->isGuest ?: 'col-md-offset-4' ?>">
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-default pull-right']); ?>
                    <?php else: ?>
                        <a href="/account/login-comment" class="btn btn-default pull-right modalForm">Отправить</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
    
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '
        <span class="sort_name">Сортировка:</span> {sorter}
        <div class="comments-container">
            <ul id="comments-list-ul" class="comments-list">
                <li>
                    <div class="" id="comments-list">
                        <div class="items">
                            {items}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        ',
        'sorter' => [
            'attributes'    => [
                'likes',
                'time_create',
                'user_id'
            ],
        ],
        'itemView' => '_level1',
        /*'pager' => [
            'options' => ['class' => ''],
        ],*/
        'summary' => false,
        'emptyText' => 'Комментарии отсутствуют.',
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<script>
    function copyToClipboard(text) {
        window.prompt("Для копирования ссылки воспользуйтесь комбинацией кнопок на клавиатуре для Windows: Ctrl+C и для MAC OS: CMD+C", text);
    }
</script>