<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 11.09.15
 * Time: 21:52
 */

namespace common\components\clients;


use Yii;

class VKontakte extends \yii\authclient\clients\VKontakte
{
    public function sharePost()
    {
        Yii::$app->controller->redirect('/site/vk');
    }
}