<?php

namespace common\components\helpers;
/**
 * Created by PhpStorm.
 * User: Evgenii Rogozhuk
 * Date: 14.04.19
 * Time: 2:09
 */
class MonthHelper extends \yii\helpers\StringHelper
{
    public static function toWords($string)
    {
        $month = '';
            
        switch ($string) {
            case 1: 
                return $month = 'Январь';
            case 2:
                return $month = 'Февраль';
            case 3:
                return $month = 'Март';
            case 4:
                return $month = 'Апрель';
            case 5:
                return $month = 'Май';
            case 6:
                return $month = 'Июнь';
            case 7:
                return $month = 'Июль';
            case 8:
                return $month = 'Август';
            case 9:
                return $month = 'Сентябрь';
            case 10:
                return $month = 'Октябрь';
            case 11:
                return $month = 'Ноябрь';
            case 12:
               return $month = 'Декабрь';
        }
        
        return $month;
    }
}