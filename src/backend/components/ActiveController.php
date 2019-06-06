<?php

namespace backend\components;

use common\models\News;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use zxbodya\yii2\galleryManager\GalleryManagerAction;

class ActiveController extends Controller
{
    public $layout = '//column2';
    public $modelClass;
    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;


    public function actions()
    {
        return [
            'index' => [
                'class' => 'backend\components\actions\IndexAction',
                'controller' => $this,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'afterLoad' => [$this, 'afterLoadIndex'],
            ],
            'update' => [
                'class' => 'backend\components\actions\UpdateAction',
                'controller' => $this,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
                'afterLoad' => [$this, 'afterLoadUpdate'],
                'beforeRender' => [$this, 'beforeRender'],
            ],
            'delete' => [
                'class' => 'backend\components\actions\DeleteAction',
                'modelClass' => $this->modelClass,
                'controller' => $this,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'set' => [
                'class' => 'backend\components\actions\SetAction',
                'modelClass' => $this->modelClass,
                'controller' => $this,
                'checkAccess' => [$this, 'checkAccess'],
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
                        'actions' => ['index', 'delete', 'update', 'set', 'galleryApi'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function checkAccess($action, $model = null, $params = []){}

    public function afterLoadUpdate(&$model){}

    public function afterLoadIndex(&$model){}

    public function beforeRender(&$model){}

    public function beforeRenderIndex(&$model){}
}