<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\rbac\Permission;
use yii\web\Controller;

class RbacController extends Controller
{
    static $permissions = [];

    public function init()
    {
        self::$permissions = [
            //final
            User::ROLE_ADMIN => [
                'backend',
                'backend.content',
                'backend.contact',
                'backend.requests',
                'backend.main',
                'backend.main.setting',
                'backend.users',
                'frontend',
            ],

            User::ROLE_USER => [
                'frontend.account',
            ],

            User::ROLE_GUEST => [
                //frontend
                'frontend.book',
                'frontend.page',
                'frontend.account.login',
                'frontend.account.signup',
                'frontend.account.recovery',
                'frontend.user',
                'frontend.cron',
                //backend
                'backend.main.main.error',
                'backend.main.main.login',
            ],
        ];
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $roles = $auth->getRoles();
        $rules = $auth->getRules();
        $permissions = self::$permissions;

        //remove old perms
        foreach ($roles as $role) {
            $perms = $auth->getPermissionsByRole( $role->name );
            foreach ($perms as $perm) {
                $auth->remove($perm);
            }
        }

        //remove old rules
        foreach ($rules as $rule) {
            $auth->remove($rule);
        }

        $this->createPermissionsTree();

        //add new perms
        foreach ($roles as $role) {
            if (isset($permissions[ $role->name ]))
                foreach ($permissions[ $role->name ] as $permissionId => $ruleClass) {
                    $this->fixKeysVals($permissionId, $ruleClass);

                    $permission = $auth->getPermission($permissionId);

                    //creating permission if not exist
                    if (empty($permission)) {
                        $permission = $auth->createPermission($permissionId);
                        $auth->add($permission);
                    }

                    //setting ruleName
                    if (isset($ruleClass)) {
                        $rule = new $ruleClass();
                        $auth->add($rule);

                        $permission->ruleName = $rule->name;
                        $auth->update($permissionId, $permission);
                    }

                    $auth->addChild($role, $permission);
                }
        }
    }

    protected function createPermissionsTree()
    {
        $auth = Yii::$app->authManager;
        $uniquePermissions = [];

        foreach (self::$permissions as $rolePermissions) {
            foreach ($rolePermissions as $permissionId => $ruleName) {
                $this->fixKeysVals($permissionId, $ruleName);

                if (!in_array($permissionId, $uniquePermissions)) {
                    $uniquePermissions[] = $permissionId;
                }
            }
        }

        sort($uniquePermissions);

        //finding BASE (0 level) permissions (frontend, backend) and build tree
        foreach ($uniquePermissions as $permissionId) {
            if (!strpos($permissionId, '.')) {
                $permission = $auth->getPermission($permissionId);

                if (!isset($permission)) {
                    $permission = $auth->createPermission($permissionId);
                    $auth->add($permission);
                }

                $this->createNode($uniquePermissions, $permission, 1);
            }
        }
    }

    /**
     * Fixing key and values to handle beautiful tree of perms and rules
     * @param $key int|string permission id
     * @param $val string permission id / rule name
     */
    protected function fixKeysVals(&$key, &$val)
    {
        if (is_integer($key)) {
            $key = $val;
            $val = null;
        }
    }

    /**
     * Create node for current permission
     * @param $permissions string[]
     * @param $parentPermission Permission
     * @param $level int
     */
    protected function createNode($permissions, &$parentPermission, $level)
    {
        $auth = Yii::$app->authManager;

        foreach ($permissions as $permission) {
            if (strpos($permission, $parentPermission->name) !== false && substr_count($permission, '.') == $level) {
                $currentPermission = $auth->getPermission($permission);

                if (!isset($currentPermission)) {
                    $currentPermission = $auth->createPermission($permission);
                    $auth->add($currentPermission);
                }

                if (!$auth->hasChild($parentPermission, $currentPermission)) {
                    $auth->addChild($parentPermission, $currentPermission);
                }

                $this->createNode($permissions, $currentPermission, ++$level);
            }
        }
    }

}