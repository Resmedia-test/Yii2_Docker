<?php
use common\models\Comment;
use frontend\components\OutLink;

?>

<div class="replay-comment-box" itemscope="itemscope" itemtype="http://schema.org/Comment">
    <a class="comment-padding"  name="comment<?= $model->id ?>"></a>
    <div class="comment-avatar">
        <img
                title="<?= $model->getUserName() ?>"
                alt="<?= $model->getUserName() ?>"
                class="main-comment-user-img"
                src="<?= $model->getUserCover() ?>"
        >
    </div>

    <?php if ($model->status == 1): ?>
        <div class="comment-box">
            <div class="comment-head">
                <h6 class="comment-name">
                    <span style="display:none" itemprop="datePublished"><?= date('Y-m-d\TH:i:s', $model->time_create) ?></span>

                    <span class="date" itemprop="creator"><?= $model->getUserName() ?></span>

                    <span class="date">
                        <?= Yii::$app->formatter->asDate($model->time_create, 'short') ?>
                        в <?= Yii::$app->formatter->asTime($model->time_create, 'short') ?>
                    </span>
                </h6>

            </div>
            <div class="comment-content">
                <article id="comment-text" itemprop="text">
                    <p class="date">К сожалению автор удалил свой комментарий</p>
                </article>
            </div>
        </div>
    <?php else: ?>
        <div class="comment-box">
            <div class="comment-head">
                <h6 class="comment-name">
                    <span style="display:none" itemprop="datePublished"><?= date('Y-m-d\TH:i:s', $model->time_create) ?></span>
                    <span itemprop="creator"><?= $model->getUserName() ?></span>
                    <span class="date">
                        <?= Yii::$app->formatter->asDate($model->time_create, 'short') ?>
                        в
                        <?= Yii::$app->formatter->asTime($model->time_create, 'short') ?>
                        отвечает пользователю:
                        <a title="Перейти к комментарию #<?= $model->reply_id ?>"
                           href="#comment<?= $model->reply_id ?>">
                            @<?= $model->reply->getUserName() ?>
                        </a>
                    </span>
                </h6>
                <div class="pull-right bt">
                    <?php if (Yii::$app->user->can(Comment::RBAC_FRONTEND_CREATE, ['status' => Yii::$app->user->status])): ?>
                        <?php if (Yii::$app->user->can(Comment::RBAC_FRONTEND_UPDATE, ['owner_id' => $model->user_id])): ?>
                            <?php if (time() - $model->time_create <= Comment::COMMENT_UPDATE_DELAY): ?>
                                <a title="Редактировать комментарий" class="modalForm"
                                   href="/comment/create?id=<?= $model->id ?>" data-id="<?= $model->id ?>"><i
                                        class="ic ic-edit"></i> </a>
                                <a title="Удалить комментарий" href="#deleteComment" data-id="<?= $model->id ?>"><i
                                        class="ic ic-remove"></i> </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a title="Жалоба на комментарий" href="#abuseComment" data-id="<?= $model->id ?>"><i
                                    class="ic ic-fire"></i></a>
                            <a class="like" title="Нравится" href="#likeComment"
                               data-id="<?= $model->id ?>"><i
                                    class="ic ic-thumbs-up"></i><span><?= ($model->likes ? $model->likes : "") ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a
                        href=""
                        title="Получить ссылку на пост"
                        class="pull-left"
                        onclick="copyToClipboard(document.getElementById('onlink<?= $model->id ?>').innerHTML)"
                    >
                        <span id="onlink<?= $model->id ?>" class="hidden">
                            https://<?= $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]; ?>#comment<?= $model->id ?>
                        </span>
                        <i class="ic ic-magnet rotate90"></i>
                    </a>
                </div>
            </div>
            <div class="comment-content">
                <article id="comment-text" itemprop="text">
                    <p><?= OutLink::load()->process($model->text) ?></p>
                </article>
                <a
                        title="Ответить на комментарий"
                        class="pull-right reply-comment"
                        href="#replyComment"
                        data-id="<?= $model->id ?>"
                        data-author="<?= $model->getUserName() ?>"
                >
                    Ответить
                </a>
            </div>
        </div>
    <?php endif; ?>


</div>