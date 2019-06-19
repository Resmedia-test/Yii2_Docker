<?php
/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 15.05.16
 * Time: 13:01
 */

namespace frontend\widgets;

use common\models\Article;
use yii\base\Widget;
use yii\helpers\Html;

class ArticleList extends Widget
{
    public $count = 14;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $article = Article::find()
                ->where(['status' => 1])
                ->orderBy('time_create DESC')
                ->limit($this->count)
                ->all();

        return $this->render('articleList', [
            'article' => $article,
        ]);
    }
}