<?php
namespace backend\components\actions;

use Yii;
use yii\data\ActiveDataProvider;

class IndexAction extends Action
{
    public $afterLoad;

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]].
     */
    public $prepareDataProvider;


    /**
     * @return string
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $model = new $this->modelClass();
        $model->load(Yii::$app->request->get());

        call_user_func_array($this->afterLoad, [&$model]);

        return $this->controller->render('index', [
            'dataProvider' => $this->prepareDataProvider($model),
            'filterModel' => method_exists($model, 'search') ? $model : null,
        ]);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    
    protected function prepareDataProvider($model)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        return method_exists($model, 'search')
            ? $model->search(Yii::$app->request->get())
            : new ActiveDataProvider([
                'query' => $model::find(),
                'pagination' => [
                    'pageSize' => 10,
                ]
            ]);
    }
}