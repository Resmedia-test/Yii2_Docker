<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_023150_rbac_roles extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');
        $guest = $auth->createRole('guest');

        $auth->add($admin);
        $auth->add($user);
        $auth->add($guest);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->remove( $auth->getRole('admin') );
        $auth->remove( $auth->getRole('guest') );
        $auth->remove( $auth->getRole('user') );
    }
}
