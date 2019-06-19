<?php

namespace frontend\widgets;

use common\models\Auth;
use Yii;
use yii\authclient\widgets\AuthChoiceItem;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    public static $iconsList = [
        'vkontakte' => 'icon icon-vk',
        'facebook' => 'icon icon-facebook',
        'twitter' => 'icon icon-twitter',
        'odnoklassniki' => 'icon icon-odnoklassniki',
    ];

    public function clientLink($client, $text = null, array $htmlOptions = [])
    {
        if ($text === null) {
            $text = Html::tag('i', '', ['class' => self::$iconsList[ $client->getName() ]]);
        }

        if (!array_key_exists('class', $htmlOptions)) {
            $htmlOptions['class'] = 'auth-link ' . $client->getName();
        }


        if(!Yii::$app->user->isGuest)
        {
            $auth = Auth::findOne(['user_id' => Yii::$app->user->id, 'source' => $client->getId()]);

            if($auth)
            {
                $htmlOptions['class'] = $htmlOptions['class'] . ' disabled';
                $htmlOptions['onClick'] = 'return false;';
            }
        }


        $viewOptions = $client->getViewOptions();
        if (empty($viewOptions['widget'])) {
            if ($this->popupMode) {
                if (isset($viewOptions['popupWidth'])) {
                    $htmlOptions['data-popup-width'] = $viewOptions['popupWidth'];
                }
                if (isset($viewOptions['popupHeight'])) {
                    $htmlOptions['data-popup-height'] = $viewOptions['popupHeight'];
                }
            }
            echo Html::a($text, $this->createClientUrl($client), $htmlOptions);
        } else {
            $widgetConfig = $viewOptions['widget'];
            if (!isset($widgetConfig['class'])) {
                throw new InvalidConfigException('Widget config "class" parameter is missing');
            }
            /* @var $widgetClass Widget */
            $widgetClass = $widgetConfig['class'];
            if (!(is_subclass_of($widgetClass, AuthChoiceItem::class))) {
                throw new InvalidConfigException('Item widget class must be subclass of "' . AuthChoiceItem::class . '"');
            }
            unset($widgetConfig['class']);
            $widgetConfig['client'] = $client;
            $widgetConfig['authChoice'] = $this;
            echo $widgetClass::widget($widgetConfig);
        }
    }

    protected function renderMainContent()
    {
        $items = [];
        foreach ($this->getClients() as $externalService) {
            $items[] = Html::tag('div', $this->clientLink($externalService));
        }
    }
}