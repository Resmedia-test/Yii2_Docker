<?php
namespace backend\modules\main\controllers;

use backend\components\Controller;
use common\models\Article;
use common\models\CommentAbuse;
use common\models\Directway;
use common\models\Lifestyle;
use common\models\News;
use Yii;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Main controller
 */
class MainController extends Controller
{
    public $layout = '//error';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\components\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        $this->layout = '//column2';

        return $this->render('index', []);
        
    }

    public function actionLogin()
    {
        $this->layout = '/login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $this->layout = false;

        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionUpload()
    {
        $this->layout = false;

        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = FileHelper::getMimeType($uploadedFile->tempName);
        $file = time()."_".$uploadedFile->name;

        $url = Yii::$app->urlManager->createAbsoluteUrl('/uploads/ccontent/'.$file);
        $uploadPath = Yii::getAlias('@webroot').'/uploads/ccontent/'.$file;
        //extensive suitability check before doing anything with the file…
        if ($uploadedFile==null)
        {
            $message = "No file uploaded.";
        }
        else if ($uploadedFile->size == 0)
        {
            $message = "The file is of zero length.";
        }
        else if ($mime!="image/jpeg" && $mime!="image/png")
        {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        }
        else if ($uploadedFile->tempName==null)
        {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        }
        else {
            $message = "";
            $move = $uploadedFile->saveAs($uploadPath);
            if(!$move)
            {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
        }
        $funcNum = $_GET['CKEditorFuncNum'] ;
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }
}
