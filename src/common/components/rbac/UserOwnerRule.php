<?php

namespace common\components\rbac;

use Yii;
use yii\rbac\Rule;

class UserOwnerRule extends Rule
{
    public $name = 'userOwner';

    public function execute($user, $item, $params)
    {
        return !Yii::$app->user->isGuest && @$params['owner_id'] == Yii::$app->user->id ?: false;
    }
}
