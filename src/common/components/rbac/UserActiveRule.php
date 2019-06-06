<?php

namespace common\components\rbac;

use common\models\User;
use yii\rbac\Rule;

class UserActiveRule extends Rule
{
    public $name = 'userActive';

    public function execute($user, $item, $params)
    {
        return @$params['status'] == User::STATUS_ACTIVE ? true : false;
    }
}
