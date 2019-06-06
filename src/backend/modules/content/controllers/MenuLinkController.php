<?php

namespace backend\modules\content\controllers;

use backend\components\Controller;
use Yii;
use yii\filters\AccessControl;
use common\models\Menu;
use common\models\MenuLink;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use himiklab\sortablegrid\SortableGridAction;

/**
 * MenuLinkController implements the CRUD actions for MenuLink model.
 */
class MenuLinkController extends Controller
{
    public $layout = '//column2';

    public function actions()
    {
        return [
            'sort' => [
                'class' => SortableGridAction::class,
                'modelName' => MenuLink::class,
            ],
        ];
    }

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
                        'actions' => ['index', 'delete', 'update', 'sort'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all MenuLink models.
     * @return mixed
     */
    public function actionIndex($menu_id)
    {
        $menu = Menu::findOne($menu_id);
        if($menu == null)
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $dataProvider = new ActiveDataProvider([
            'query' => MenuLink::find()->where(['menu_id' => $menu_id]),
            'sort' => [
                // Set the default sort by name ASC and created_at DESC.
                'defaultOrder' => [
                    'order' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'menu' => $menu,
        ]);
    }

    /**
     * Updates an existing MenuLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id=null, $menu_id)
    {
        $this->layout = false;

        $model = $this->findModel($id);

        $menu = Menu::findOne($menu_id);
        if($menu == null || (!empty($model->menu_id) && $model->menu_id !== (int)$menu_id))
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $model->menu_id = $menu_id;

        $new = $model->isNewRecord;

        if($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->isAjax && isset($_POST['ajax']))
            {
                Yii::$app->response->format = 'json';
                return \yii\widgets\ActiveForm::validate($model);
            }

            if ($model->save())
            {
                return false;
            }
            else
                return $model->errors;
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'menu' => $menu,
        ]);
    }

    /**
     * Deletes an existing MenuLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $menu_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'menu_id' => $menu_id]);
    }

    /**
     * Finds the MenuLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if($id==null)
            return new MenuLink();

        if (($model = MenuLink::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
