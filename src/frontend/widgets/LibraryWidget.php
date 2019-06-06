<?php
/**
 * Created by PhpStorm.
 * User: ResMedia
 * Date: 04.05.16
 * Time: 08:06
 */

namespace frontend\widgets;

use common\models\Book;
use yii\base\Widget;
use yii\helpers\Html;

class LibraryWidget extends Widget
{
    public $count = 10;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $book =  Book::find()
                ->where(['visible' => 1])
                ->orderBy('time_create DESC')
                ->limit($this->count)
                ->all();

        return $this->render('libraryWidget', [
            'book' => $book,
        ]);
    }
}