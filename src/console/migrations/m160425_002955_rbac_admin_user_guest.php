<?php

use common\models\AuthAssignment;
use common\models\User;
use yii\db\Migration;

class m160425_002955_rbac_admin_user_guest extends Migration
{
    //permissions for guest
    static $permissionsGuest = [
        //frontend
        'frontend.book',
        'frontend.page',
        'frontend.account.login',
        //backend
        'backend.main.main.error',
        'backend.main.main.login',
    ];

    //permissions for user
    static $permissionsUser = [
        //frontend
        'frontend.account',
    ];

    //permissions for admin
    static $permissionsAdmin = [
        //backend
        'backend',
    ];

    public function up()
    {
        $auth = Yii::$app->authManager;

        //creating roles
        $roles = [
            User::ROLE_ADMIN => $auth->getRole( User::ROLE_ADMIN ),
            User::ROLE_USER => $auth->createRole( User::ROLE_USER ),
            User::ROLE_GUEST => $auth->getRole( User::ROLE_GUEST ),
        ];

        //process guest permissions
        foreach (self::$permissionsGuest as $id)
        {
            $permission = $auth->createPermission($id);
            $auth->add($permission);
            $auth->addChild($roles[User::ROLE_GUEST], $permission);
        }

        //adding guest as child for user role
        $auth->addChild($roles[User::ROLE_USER], $roles[User::ROLE_GUEST]);

        //process user permissions
        foreach (self::$permissionsUser as $id)
        {
            $permission = $auth->createPermission($id);
            $auth->add($permission);
            $auth->addChild($roles[User::ROLE_USER], $permission);
        }

        //adding user as child for admin role
        $auth->addChild($roles[User::ROLE_ADMIN], $roles[User::ROLE_USER]);

        //process admin permissions
        foreach (self::$permissionsAdmin as $id)
        {
            $permission = $auth->createPermission($id);
            $auth->add($permission);
            $auth->addChild($roles[User::ROLE_ADMIN], $permission);
        }
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $roles = [
            User::ROLE_ADMIN => $auth->getRole( User::ROLE_ADMIN ),
            User::ROLE_USER => $auth->getRole( User::ROLE_USER ),
            User::ROLE_GUEST => $auth->getRole( User::ROLE_GUEST ),
        ];

        //process admin permissions
        foreach (self::$permissionsAdmin as $id)
        {
            $permission = $auth->getPermission($id);
            $auth->removeChild($roles[User::ROLE_ADMIN], $permission);
            $auth->remove($permission);
        }

        //process user permissions
        foreach (self::$permissionsUser as $id)
        {
            $permission = $auth->getPermission($id);
            $auth->removeChild($roles[User::ROLE_USER], $permission);
            $auth->remove($permission);
        }

        //process guest permissions
        foreach (self::$permissionsGuest as $id)
        {
            $permission = $auth->getPermission($id);
            $auth->removeChild($roles[User::ROLE_GUEST], $permission);
            $auth->remove($permission);
        }
    }
}
