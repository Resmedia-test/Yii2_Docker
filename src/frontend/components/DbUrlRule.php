<?php
/**
 * Created by PhpStorm.
 * User: resmedia
 * Date: 23.03.15
 * Time: 18:12
 */

namespace frontend\components;

use common\models\Book;
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
            $module = '';//!empty($section->module) ? $section->module.'/' : '';
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

            if (strpos(Yii::$app->request->getPathInfo(), '/')) {
                $dependency = new DbDependency(['sql' => 'SELECT max(time_update) from books']);

                $model = Book::getDb()->cache(function ($db) {
                    $pathInfo = Yii::$app->request->getPathInfo();
                    $pathInfo = strrev($pathInfo);
                    //$url = substr($pathInfo, strpos($pathInfo, '/')+1, strlen($pathInfo)-strpos($pathInfo, '/'));
                    $url = substr($pathInfo, 0, strpos($pathInfo, '/'));
                    $url = strrev($url);

                    $model = Book::find()->where(['like', 'url', $pathInfo]);

                    if (is_integer($url)) {
                        $model->orWhere(['id' => $url]);
                    }

                    return $model->one();
                }, 0, $dependency);

                if (isset($model)) {
                    $action = 'view';
                    $params['url'] = !empty($model->url) ? $model->url : null;
                    $params['id'] = !empty($model->id) && empty($model->url) ? $model->id : null;
                } else {
                    return false;
                }
            }

            $route = $module . $controller . $action;

            return [$route, $params];
        }

        return false;
    }
}
