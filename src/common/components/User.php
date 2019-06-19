<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 01.10.15
 * Time: 12:49
 */

namespace common\components;

use Yii;

class User extends \yii\web\User
{
    /**
     * @var \common\models\User;
     */
    private $_model = null;

    public function getModel()
    {
        if (!$this->isGuest && !isset($this->_model)) {
            $user = new $this->identityClass();

            $this->_model = $user::findOne( $this->id );
        }

        return $this->_model;
    }

    public function getUsername()
    {
        return $this->getModel() ? $this->getModel()->name : '';
    }

    public function getRoles()
    {
        return Yii::$app->authManager->getRolesByUser( $this->id );
    }

    public function getStatus()
    {
        return $this->getModel() ? $this->getModel()->status : \common\models\User::STATUS_INACTIVE;
    }

    public function refreshTimeArticle()
    {
        $model = $this->getModel();
        $model->touch('time_article');
        $model->updateAttributes(['time_article']);
    }

    public function setRate($entity, $id, $rate)
    {
        if ($this->isGuest) {
            return false;
        }

        $model = $this->getModel();

        $rates = unserialize($model->rates);

        if (!isset($rates[$entity][$id])) {
            $rates[$entity][$id] = $rate;
        }

        $model->rates = serialize($rates);
        $model->updateAttributes(['rates']);

        return true;
    }

    /**
     * @return string
     */

    public function hasRate($entity, $id)
    {
        if ($this->isGuest) {
            return false;
        }

        $model = $this->getModel();

        $rates = unserialize($model->rates);

        return isset($rates[$entity][$id]);
    }

    public function getRate($entity, $id)
    {
        if ($this->isGuest) {
            return null;
        }

        $model = $this->getModel();

        $rates = unserialize($model->rates);

        return @$rates[$entity][$id] ?: false;
    }

    public function superUser(){
        return array_key_exists(\common\models\User::ROLE_ADMIN, Yii::$app->user->getRoles());
    }

    /**
     * @param string $permissionName constructed permission name (app.module.controller.action)
     * @param array $params
     * @param bool|true $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        $auth = Yii::$app->authManager;

        //1 level
        if ($auth->getPermission($permissionName) !== null) {
            return parent::can($permissionName, $params, $allowCaching);
        }

        //next levels
        while (strpos($permissionName, '.')) {
            //cut off last namespace
            $pos = strlen($permissionName) - strpos(strrev($permissionName), '.');
            $permissionName = substr($permissionName, 0, --$pos);

            if ($auth->getPermission($permissionName) !== null) {
                return parent::can($permissionName, $params, $allowCaching);
            }
        }

        return false;
    }
}