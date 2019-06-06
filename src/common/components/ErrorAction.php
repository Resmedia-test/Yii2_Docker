<?php

namespace common\components;

use common\models\Setting;
use Yii;
use yii\web\HttpException;

class ErrorAction extends \yii\web\ErrorAction
{
    const SETTING_EMAIL_NOTIFY_404 = 'adminEmail';

    public function run()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) !== null && $exception instanceof HttpException)
        {
            $code = $exception->statusCode;
            if($code == 404)
            {
                /*$emailNotify404 = Setting::find()->where(['code' => self::SETTING_EMAIL_NOTIFY_404])->one();

                if(isset($emailNotify404))
                {
                    $email = Yii::$app->mailer->compose(['html' => 'notify404Admin-html'], ['exception' => $exception]);
                    $email->setFrom(['info@'.$_SERVER['HTTP_HOST'] => 'ON!']);
                    $email->setSubject('Уведомление о 404 ошибке');
                    $email->setTo($emailNotify404->value);

                    $email->send();
                }*/
            }

            if($code == 404 || $code == 403)
                $this->view = $this->id . $code;
        }

        return parent::run();
    }
}