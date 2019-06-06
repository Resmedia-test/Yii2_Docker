<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@webroot';

    public $publishOptions = [
        'forceCopy' => false,
        //'appendTimestamp' => true,
    ];

  /*  public $css = [
        'twbs/bootstrap/dist/css/bootstrap.css?_v{hash}'
    ];

    public $js = [
        'twbs/bootstrap/dist/js/bootstrap.js'
    ];*/

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init() {
        $this->jsOptions['position'] = View::POS_END;
        parent::init();
    }
}
