<?php
/**
 * Created by PhpStorm.
 * User: Resmedia
 * Date: 14.04.16
 * Time: 21:40
 */

namespace frontend\controllers;

use common\models\Subscription;
use Yii;
use frontend\components\Controller;
use yii\console\Exception;
use yii\widgets\ActiveForm;

class SubscriptionController extends Controller
{

    public function checkPermissions()
    {
        return true;
    }

    public function actionForm($email)
    {

        return $this->renderPartial('form', ['email' => $email]);
    }

    public function actionGuest()
    {
        if (!Yii::$app->user->isGuest) {
            throw new Exception('Подписываться на новости могут только гости.');
        }

        $model = new Subscription(['scenario' => Subscription::SCENARIO_SUBSCRIBE]);

        if (!empty($_POST)) {

            $model->name = Yii::$app->request->post('name');
            $model->email = Yii::$app->request->post('email');
            $model->articles = Yii::$app->request->post('articles', 0) == 0 ? 0 : 1;

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Спасибо! Вы успешно подписаны.');
                    $this->redirect('/');
                    return json_encode(['status' => 0]);
                } else {
                    return json_encode(['status' => 1, 'error' => implode(', ', $model->getFirstErrors())]);
                }
            } else {
                return json_encode(['status' => 2, 'error' => implode(', ', $model->getFirstErrors())]);
            }
        }
    }

    public function actionUnsubscribe($id, $email)
    {
        $deleted = Subscription::deleteAll(['id' => $id, 'email' => $email]);

        if ($deleted) {
            Yii::$app->session->setFlash('success', 'Вы успешно отписались от рассылки!');
        }

        return $this->redirect('/');
    }
}