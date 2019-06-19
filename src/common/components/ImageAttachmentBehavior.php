<?php

namespace common\components;

use yii\imagine\Image;

class ImageAttachmentBehavior extends \zxbodya\yii2\imageAttachment\ImageAttachmentBehavior
{

    public function setImage($path)
    {
        $this->checkDirectories();

        $originalImage = Image::getImagine()->open($path);
        //save image in original size

        //create image preview for gallery manager
        foreach ($this->versions as $version => $fn) {
            /** @var Image $image */

            call_user_func($fn, $originalImage)
                ->save($this->getFilePath($version), ['quality' => 100]);
        }
    }


    public function getImageId()
    {
        $pk = $this->owner->getPrimaryKey();
        if (is_array($pk)) {
            return implode('_', $pk);
        } else {
            return $pk;
        }
    }

    private function checkDirectory($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
    }

    private function checkDirectories()
    {
        if (!file_exists($this->directory)) {
            $this->checkPath();
        }

        $this->checkDirectory($this->directory . '/' . $this->getImageId());
    }

    private function checkPath()
    {
        $parts = explode('/', rtrim($this->directory, '/'));
        $i = 0;

        $path = implode('/', array_slice($parts, 0, count($parts) - $i));
        while (!file_exists($path)) {
            $i++;
            $path = implode('/', array_slice($parts, 0, count($parts) - $i));
        }
        $i--;
        $path = implode('/', array_slice($parts, 0, count($parts) - $i));
        while ($i >= 0) {
            mkdir($path, 0777);
            $i--;
            $path = implode('/', array_slice($parts, 0, count($parts) - $i));
        }
    }
}