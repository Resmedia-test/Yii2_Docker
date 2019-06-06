<?php

/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 04.04.16
 * Time: 0:14
 */
namespace app\components;

use yii\base\Widget;

class MistakeWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    function run()
    {
        return $this->render('mistake');
    }
}