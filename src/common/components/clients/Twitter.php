<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 10.09.15
 * Time: 11:14
 */

namespace common\components\clients;


class Twitter extends \yii\authclient\clients\Twitter
{
    public $via;

    public function getUserAttributes()
    {
        $userAttributes = parent::getUserAttributes();

        $userAttributes['first_name'] = $userAttributes['name'];

        return $userAttributes;
    }
}