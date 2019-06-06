<?php

namespace frontend\controllers;


use common\models\Book;
use common\models\Section;
use frontend\components\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{
    public function actionIndex($section_id = null)
    {
        $section = Section::findOne($section_id);
        if (!isset($section))
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $this->model = $section;

        $model = new Book();
        $dataProvider = $model->searchUser(Yii::$app->request->get(), $section_id);

        return $this->render('index', [
            'section' => $section,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionView($id = null, $url = null, $section_id = null)
    {
        $section = Section::findOne($section_id);
        if (!isset($section))
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $params = ['section_id' => $section_id];

        if (isset($id))
            $params['id'] = $id;

        if (isset($url))
            $params['url'] = $url;

        $model = Book::find()->where($params)->one();

        if (!empty($model->parent) && !strpos(Yii::$app->request->getPathInfo(), $model->parent->url))
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');

        $this->model = $model;

        return $this->render('view', [
            'section' => $section,
            'model' => $model,
        ]);
    }
}