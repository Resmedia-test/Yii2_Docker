<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\Comment;
use common\models\CommentAbuse;
use common\models\SubscriptionComment;
use frontend\components\Controller;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\widgets\ActiveForm;

class CommentController extends Controller
{
    public function actionCreate($id = null)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        if ((isset($_POST['id']) && !empty($_POST['id'])) || isset($id)) {
            $model = Comment::findOne(isset($id) ? $id : $_POST['id']);
        } else {
            $model = new Comment();
        }

        if (isset($model) && $model->load(Yii::$app->request->post()) && ($model->isNewRecord || (!$model->isNewRecord && $model->user_id == Yii::$app->user->id))) {
            $model->ip = Yii::$app->request->userIP;

            if (!isset($id)) {
                $model->user_id = Yii::$app->user->id;

                //setting allowed reply_id (only two levels)
                if (!empty($model->reply_id)) {
                    $reply = Comment::findOne($model->reply_id);

                    if (!empty($reply)) {
                        if ($reply->parent_id) {
                            $model->parent_id = $reply->parent_id;
                        } else {
                            $model->parent_id = $reply->id;
                        }
                    } else {
                        $model->reply_id = 0;
                    }
                }
            }

            Yii::$app->response->format = 'json';


            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                return ActiveForm::validate($model);
            }

            if ($model->validate() && $model->save(false)) {
                $modelClass = $model->getStringModel($model->model);
                $entityClass = new $modelClass();

                if (empty($id)) {
                    //TODO: replace with 1 sql
                    $entity = $entityClass::findOne($model->model_id);
                    $entity->time_update = time();
                    $entity->updateAttributes(['comments', 'time_update']);
                }
                //$entityClass::updateAllCounters(['comments' => +1, 'time_update' => time()], ['id' => $model->model_id]);

                return array(
                    'status' => 'success',
                    'success' => '',
                );
            } else {
                return array(
                    'status' => 'error',
                    'error' => array('comment-text' => 'Часто отправляете! Подождите 1 минуту...'),
                );
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = Comment::findOne($id);

            if (isset($model) && $model->user_id == Yii::$app->user->id) {
                $model->deleted = 1;
                $model->time_update = time();
                $model->updateAttributes(['deleted', 'time_update']);

                Yii::$app->end();
            }
        }

        throw new BadRequestHttpException();
    }

    public function actionLike($id)
    {
        if (!Yii::$app->request->cookies->has('commentsLikes')) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'commentsLikes',
                'value' => serialize([]),
            ]));
        }

        $cookie = Yii::$app->request->cookies->get('commentsLikes');
        $likes = unserialize(isset($cookie) ? $cookie->value : '');

        if (!isset($likes[$id])) {
            $model = Comment::findOne($id);

            if (isset($model)) {
                $model->likes += 1;
                $model->time_update = time();
                $model->updateAttributes(['likes', 'time_update']);

                $likes[$id] = $model->id;

                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'commentsLikes',
                    'value' => serialize($likes),
                ]));

                Yii::$app->response->format = 'json';
                return ['likes' => $model->likes];
            }
        }

        throw new BadRequestHttpException();
    }

    public function actionSubscribe($type_id, $model, $model_id)
    {
        switch ($model) {
            case 'Article':
                $modelClass = Article::class;
                break;
            default:
                $modelClass = Article::class;
        }

        if (Yii::$app->user->isGuest || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        if ($type_id == SubscriptionComment::TYPE_NONE) {
            SubscriptionComment::deleteAll([
                'model' => $model,
                'model_id' => $model_id,
                'user_id' => Yii::$app->user->id,
            ]);
        } else {
            $subscription = SubscriptionComment::find()->where([
                'model' => $model,
                'model_id' => $model_id,
                'user_id' => Yii::$app->user->id,
            ])->one();

            if (!isset($subscription)) {
                $subscription = new SubscriptionComment();
                $subscription->model = $model;
                $subscription->model_id = $model_id;
                $subscription->user_id = Yii::$app->user->id;
            }

            $subscription->type_id = $type_id;

            //setting comments count
            if ($type_id !== SubscriptionComment::TYPE_NOW) {
                $entity = new $modelClass();
                $entity = $entity->findOne($model_id);

                if (isset($entity) && isset($entity->comments)) {
                    $subscription->comments = $entity->comments;
                }
            }

            $subscription->save();
        }
    }

    public function actionUnsubscribe($id, $user_id)
    {
        SubscriptionComment::deleteAll(['id' => $id, 'user_id' => $user_id]);

        return $this->redirect('/');
    }

    public function actionAbuse($id)
    {
        $model = Comment::findOne($id);

        if (!isset($model)) {
            throw new BadRequestHttpException();
        }

        $abuse = new CommentAbuse();
        $abuse->comment_id = $model->id;
        $abuse->user_id = Yii::$app->user->id;

        $abuse->save();
    }
}