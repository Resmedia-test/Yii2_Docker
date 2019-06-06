<?php

namespace backend\modules\content\controllers;

use backend\components\Controller;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use common\models\Menu;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\MenuLink;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    public $layout = '//column2';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'update', 'set'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Menu();

        return $this->render('index', [
            'dataProvider' => $model->search(),
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id=null)
    {
        $this->layout = false;

        $model = $this->findModel($id);

        $new = $model->isNewRecord;

        if($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->isAjax && isset($_POST['ajax']))
            {
                Yii::$app->response->format = 'json';
                return \yii\widgets\ActiveForm::validate($model);
            }

            if ($model->save()) {
                if($new)
                    $this->redirect(['/content/menu-link/index', 'menu_id' => $model->id]);
                else
                    return false;
            }
            else
                return $model->errors;
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        MenuLink::deleteAll(['menu_id' => $id]);

        return $this->redirect(['index']);
    }

    public function actionSet($id, $attr, $val)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        if(isset($model->$attr))
            $model->updateAttributes([$attr => $val]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if($id==null)
            return new Menu();

        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
