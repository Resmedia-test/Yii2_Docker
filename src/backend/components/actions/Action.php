<?php
namespace backend\components\actions;
use backend\components\Controller;
use yii\db\ActiveRecordInterface;

class Action extends \yii\rest\Action
{
    /**
     * @var Controller controller
     */
    public $controller;


    public function findModel($id)
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        } else {
            return new $modelClass();
        }
    }
}