<?php

namespace backend\widgets;

use backend\assets\MetaTagsAsset;
use v0lume\yii2\metaTags\models\MetaTag;

class MetaTags extends \v0lume\yii2\metaTags\MetaTags
{
    public function registerClientScript()
    {
        MetaTagsAsset::register($this->getView());
    }
    
    public function run()
    {
        $this->registerClientScript();

        $model = new MetaTag;

        if(!$this->model->isNewRecord)
        {
            $meta_tag = MetaTag::findOne([
                'model_id' => $this->model->id,
                'model'  => (new \ReflectionClass($this->model))->getShortName()
            ]);

            if(isset($meta_tag))
                $model = $meta_tag;
        }

        return $this->render('MetaTags', [
            'model' => $model,
            'form' => $this->form,
        ]);
    }
}