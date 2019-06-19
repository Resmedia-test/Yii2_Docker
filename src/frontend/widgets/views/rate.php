<?php

use common\models\Article;
use kartik\rating\StarRating;
use yii\widgets\Pjax;

$rate = Yii::$app->user->getRate(Article::class, $model_id);
$mod = Article::findOne(['id' => $model_id]);
?>

<?php Pjax::begin(['id' => 'rate']); ?>
<div class="star-block" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
    <meta itemprop="worstRating" content="1">

    <?php if (Yii::$app->user->isGuest): ?>
        <span class="star-text">Оценка статьи:</span>
        <?= StarRating::widget([
            'name' => 'rating',
            'value' => @($mod->rate),
            'pluginOptions' => [
                //'theme' => 'krajee-svg',
                'size' => 'sm',
                'displayOnly' => true,
                'filledStar' => '<span class="icon icon-star-full"></span>',
                'emptyStar' => '<span class="icon icon-star-empty"></span>',
            ],
            'options' => ['style' => 'display: none;']
        ]); ?>
    <?php else: ?>
        <span class="star-text">Оценить статью:</span>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <i
                    class="article-rate icon icon-star<?= $rate && $i <= $rate ? '-full disabled' : '-empty' ?>"
                    data-rate="<?= $i ?>"
            ></i>
        <?php endfor; ?>
    <?php endif; ?>
    <div class="rate-end">
        Средняя оценка
        <span itemprop="ratingValue"><?= round($mod->rate, 2) ?></span>
        из
        <span itemprop="bestRating">5</span> |
        Основано на <?php
        $n = $mod->rates;
        switch ($n) {
            case $n < 2:
                echo "<span itemprop='reviewCount'>{$n}</span> оценке пользователя";
                break;
            case $n >= 2:
                echo "<span itemprop='reviewCount'>{$n}</span> оценках пользователей";
                break;
        } ?>
    </div>
</div>
<?php Pjax::end(); ?>
<script>
    $(document).ready(function () {
        var rate = +$(this).data('rate');

        $('.star-block').find('i.article-rate').mouseenter(function () {
            if ($('.star-block').find('i.article-rate.disabled').length) {
                return;
            }

            starsToggle(+$(this).data('rate'), true);
        }).mouseleave(function () {
            if ($('.star-block').find('i.article-rate.disabled').length) {
                return;
            }

            if (!$(this).hasClass('disabled')) {
                starsToggle(+$(this).data('rate'), false);
            }
        }).click(function () {
            var rate = +$(this).data('rate');

            if ($('.star-block').find('i.article-rate.disabled').length) {
                return;
            }

            $.ajax({
                url: '/account/rate',
                method: 'GET',
                data: {
                    id: <?=$model_id?>,
                    rate: rate,
                },
                success: function () {
                    starsToggle(rate, true, true);
                    $.pjax.reload({container: '#rate', async: false});
                }
            });
        });

        function starsToggle(rate, on, disabled) {
            var rate = rate || 1;
            var disabled = disabled || false;
            var i = 1;

            do {
                if (on) {
                    $('.star-block > i[data-rate="' + i + '"]').addClass('icon-star-full').removeClass('icon-star-empty');
                } else {
                    $('.star-block > i[data-rate="' + i + '"]').addClass('icon-star-empty').removeClass('icon-star-full');
                }

                if (disabled) {
                    $('.star-block > i[data-rate="' + i + '"]').addClass('disabled');
                }

                i++;
            } while (i <= rate);
        }
    });
</script>

