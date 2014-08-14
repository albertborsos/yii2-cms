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
                $array = [
                    self::STATUS_ACTIVE   => 'Aktív',
                    self::STATUS_INACTIVE => 'Inaktív',
                    self::STATUS_DELETED  => 'Törölt',
                ];
                break;
            case 'post_type':
                $array = [
                    'BLOG' => 'Blog bejegyzés',
                    'MENU' => 'Menüpont',
                ];
                break;
            case 'yesno':
                $array = [
                    '1' => 'Igen',
                    '0' => 'Nem',
                ];
                break;
            case 'order':
                $array = [
                    'ASC'  => 'Régebbiek elöl',
                    'DESC' => 'Frissek elöl',
                ];
                break;
        }
        if (is_null($id) && $returnArray) {
            return $array;
        } else {
            return isset($array[$id]) ? $array[$id] : $id;
        }
    }

    public static function replaceCharsToUrl($string){
        $mit    = array("á","é","í","ó","ö","ő","ú","ü","ű","Á","É","Í","Ó","Ö","Ő","Ú","Ü","Ű");
        $mire   = array("a","e","i","o","o","o","u","u","u","a","e","i","o","o","o","u","u","u");
        $string = str_replace($mit, $mire, $string);
        $slug   = preg_replace('@[\s!:;_\?=\\\+\*/%&#,]+@', '-', $string);
        $slug   = str_replace(array('.html', '.php', '.htm'), '', $slug);
        $slug   = preg_replace('/\-+/', '-', $slug);
        //this will replace all non alphanumeric char with '-'
        $slug   = mb_strtolower($slug);
        //convert string to lowercase
        $slug   = trim($slug, '-');
        //trim whitespaces

        return $slug;
    }
} 