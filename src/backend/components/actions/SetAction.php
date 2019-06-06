<?php

namespace backend\components\actions;

use yii\db\ActiveRecord;

class SetAction extends Action
{
    /**
     * Update a model attribute with given value.
     * @param mixed $id id of the model to be updated.
     * @param mixed $attr id of the model to be updated.
     * @param mixed $val id of the model to be updated.
     * @return string redirect to back
     */
    public function run($id, $attr, $val)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if(isset($model->$attr))
            $model->updateAttributes([$attr => $val]);

        return $this->controller->redirect($_SERVER['HTTP_REFERER']);
    }
}