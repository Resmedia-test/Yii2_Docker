<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files required by [[Pjax]] widget.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CommentAsset extends AssetBundle
{
    public $sourcePath = '@webroot';

    public $publishOptions = [
        'forceCopy' => false,
        'appendTimestamp' => true,
    ];

    public $js = [
        'js/js.cookie.js',
        'js/base64.js',
        'js/comments.js?1',
        'js/toastr.min.js?2',
    ];

    public $css = [
        'css/toastr.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
