<?php

namespace frontend\components;

use Yii;

class MetaTagBehavior extends \v0lume\yii2\metaTags\MetaTagBehavior
{
    public function afterSave($event)
    {
        parent::afterSave($event);

        //cleaning MetaTags after first assign
        $get = $_GET;
        unset($get['MetaTag']);
        Yii::$app->request->setQueryParams($get);

        $post = $_POST;
        unset($post['MetaTag']);
        Yii::$app->request->setBodyParams($post);
    }
}