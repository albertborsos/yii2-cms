<?php

namespace albertborsos\yii2cms\components;

class DataProvider {

    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    public static function items($category, $id = null, $returnArray = true)
    {
        $array = array();
        switch ($category) {
            case 'status':
                $array = array(
                    self::STATUS_ACTIVE   => 'Aktív',
                    self::STATUS_INACTIVE => 'Inaktív',
                    self::STATUS_DELETED  => 'Törölt',
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