<?php

namespace frontend\components;

use common\models\Section;
use Yii;
use yii\base\BaseObject;
use yii\caching\DbDependency;
use yii\web\UrlRuleInterface;
use common\models\Page;

class DbUrlRule extends BaseObject implements UrlRuleInterface
{
    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    public function parseRequest($manager, $request)
    {
        //PAGES
        $dependency = new DbDependency(['sql' => 'SELECT max(time_update) from pages']);

        $model = Page::getDb()->cache(function ($db) {
            $pathInfo = Yii::$app->request->getPathInfo();
            if($pathInfo == ''){
                $pathInfo = '/';
            }
            return Page::find()->where(['like', 'url', $pathInfo])->one();
        }, 0, $dependency);

        if (isset($model))
            return ['page/view', ['id' => $model->id]];

        if (substr(Yii::$app->request->getPathInfo(), strlen(Yii::$app->request->getPathInfo()) - 1) == '/')
            return [substr(Yii::$app->request->getPathInfo(), 0, strlen(Yii::$app->request->getPathInfo()) - 1), []];

        //SECTIONS
        $dependency = new DbDependency(['sql' => 'SELECT max(time_update) from sections']);

        $section = Section::getDb()->cache(function ($db) {
            $pathInfo = Yii::$app->request->getPathInfo();

            if (strpos($pathInfo, '/'))
                $pathInfo = substr($pathInfo, 0, strpos($pathInfo, '/'));

            return Section::find()->where(['like', 'url', $pathInfo])->one();
        }, 0, $dependency);

        if (isset($section)) {
            $module = '';
            $controller = $section->controller . '/';
            $action = $section->action;
            $params = [];

            //setting params if exists
            if (strpos($action, '?')) {
                $queryString = substr($action, strpos($action, '?') + 1);
                $action = substr($action, 0, strpos($action, '?'));
                parse_str($queryString, $params);
            }

            $params['section_id'] = $section->id;

            $route = $module . $controller . $action;

            return [$route, $params];
        }

        return false;
    }
}
