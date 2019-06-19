<?php

namespace backend\widgets;

use backend\assets\GalleryManagerAsset;
use yii\base\Exception;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class GalleryManager extends \zxbodya\yii2\galleryManager\GalleryManager
{
    public function run()
    {
        if ($this->apiRoute === null) {
            throw new Exception('$apiRoute must be set.', 500);
        }

        $images = [];
        foreach ($this->behavior->getImages() as $image) {
            $images[] = [
                'id' => $image->id,
                'rank' => $image->rank,
                'name' => (string)$image->name,
                'description' => (string)$image->description,
                'preview' => $image->getUrl('preview'),
            ];
        }

        $baseUrl = [
            $this->apiRoute,
            'type' => $this->behavior->type,
            'behaviorName' => $this->behaviorName,
            'galleryId' => $this->behavior->getGalleryId()
        ];

        $opts = [
            'hasName' => $this->behavior->hasName ? true : false,
           // 'hasDesc' => $this->behavior->hasDescription ? true : false,
            'uploadUrl' => Url::to($baseUrl + ['action' => 'ajaxUpload']),
            'deleteUrl' => Url::to($baseUrl + ['action' => 'delete']),
            'updateUrl' => Url::to($baseUrl + ['action' => 'changeData']),
            'arrangeUrl' => Url::to($baseUrl + ['action' => 'order']),
            'nameLabel' => Yii::t('galleryManager/main', 'Name'),
            //'descriptionLabel' => Yii::t('galleryManager/main', 'Description'),
            'photos' => $images,
        ];

        $opts = Json::encode($opts);
        $view = $this->getView();
        GalleryManagerAsset::register($view);
        $view->registerJs("$('#{$this->id}').galleryManager({$opts});");

        $this->options['id'] = $this->id;
        $this->options['class'] = 'gallery-manager';

        return $this->render('galleryManager');
    }
}