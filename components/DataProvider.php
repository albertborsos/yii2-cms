<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.08.07.
 * Time: 14:09
 */

namespace albertborsos\yii2cms\components;


use albertborsos\yii2cms\models\Languages;

class DataProvider {
    public static function items($category, $id = null, $returnArray = true)
    {
        $array = array();
        switch ($category) {
            case 'status_languages':
                $array = array(
                    Languages::STATUS_ACTIVE   => 'Aktív',
                    Languages::STATUS_INACTIVE => 'Inaktív',
                    Languages::STATUS_DELETED  => 'Törölt',
                );
                break;
        }
        if (is_null($id) && $returnArray) {
            return $array;
        } else {
            return isset($array[$id]) ? $array[$id] : $id;
        }
    }
} 