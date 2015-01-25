<?php

namespace albertborsos\yii2cms\components;

use albertborsos\yii2cms\models\Languages;
use albertborsos\yii2cms\models\Posts;
use albertborsos\yii2cms\models\Users;
use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\helpers\ArrayHelper;

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
            case 'post_status':
                $array = [
                    Posts::STATUS_ACTIVE   => 'Aktív',
                    Posts::STATUS_INACTIVE => 'Nem listázott',
                    Posts::STATUS_DELETED  => 'Törölt',
                ];
                break;
            case 'post_type':
                $array = [
                    Posts::TYPE_BLOG     => 'Blog bejegyzés',
                    Posts::TYPE_MENU     => 'Menüpont',
                    Posts::TYPE_DROPDOWN => 'Legördülő Menü',
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
            case 'orderNumbers':
                for($i = 1; $i <= 99; $i++){
                    $array[$i] = $i;
                }
                break;
            case 'pagesize':
                $sizes = self::items('itemsinarow');
                for($i = 1; $i <5; $i++){
                    foreach($sizes as $size){
                        $array[$size*$i] = $size*$i;
                    }
                }
                asort($array);
                break;
            case 'itemsinarow':
                $array = [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    6 => 6,
                    12 => 12,
                ];
                break;
            case 'meta-robots':
                $array = [
                    'INDEX'             => 'INDEX',
                    'NOINDEX'           => 'NOINDEX',
                    'NOINDEX, NOFOLLOW' => 'NOINDEX, NOFOLLOW',
                ];
                break;
            case 'roles':
                $array = array(
                    'guest'  => 'Vendég',
                    'reader' => 'Olvasó',
                    'editor' => 'Szerkesztő',
                    'admin'  => 'Adminisztrátor',
                );
                break;
            case 'status_user':
                $array = array(
                    Users::STATUS_ACTIVE   => 'Aktív',
                    Users::STATUS_INACTIVE => 'Inaktív',
                    Users::STATUS_DELETED  => 'Törölt',
                );
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
        $slug   = preg_replace('@[\(\)\s!:;_\?=\\\+\*/%&#,]+@', '-', $string);
        $slug   = str_replace(array('.html', '.php', '.htm'), '', $slug);
        $slug   = preg_replace('/\-+/', '-', $slug);
        //this will replace all non alphanumeric char with '-'
        $slug   = mb_strtolower($slug);
        //convert string to lowercase
        $slug   = trim($slug, '-');
        //trim whitespaces

        return $slug;
    }

    public static function renderItems($menus = [], $parents = [], &$printedItems = [], $level = 0){
        $level++;
        $menuItems = [];
        if (empty($menus)) $menus = self::getMenusByType('main');
        if (empty($parents)) $parents = self::getMenusByType('parents');
        foreach($menus as $menu){
            if (array_key_exists($menu['id'], $parents) && !array_key_exists($menu['id'], $printedItems)){
                $printedItems[$menu['id']] = 'printed';
                // ha szülő és még nem volt kiírva, akkor le kell generálni a gyermekeit
                $children = self::getMenusByType('belongsTo', $menu['id']);
                if ($level == 1){
                    $menuItems[] = [
                        'label' => $menu['name'],
                        'url' => '#',
                        'linkOptions' => [
                            'class' => 'dropdown-toggle',
                            'data-toggle' => 'dropdown',
                        ],
                        'items' => self::renderItems($children, $parents, $printedItems, $level),
                    ];
                }else{
                    $menuItems[] = [
                            'label' => $menu['name'],
                            'url' => '#',
                            'items' => self::renderItems($children, $parents, $printedItems, $level),
                        ];
                }
            }else{
                // ha nem szülő, akkor nem lesz hozzá dropdown
                $url = Posts::generateUrl($menu['id']);
                $linkOptions = [];
                if (strpos($url, Yii::$app->urlManager->baseUrl) === false){
                    $linkOptions = ['target' => '_blank'];
                }

                $menuItems[] = [
                    'label' => $menu['name'],
                    'url' => $url,
                    'linkOptions' => $linkOptions,
                ];
            }
        }

        return $menuItems;
    }

    public static function getMenusByType($type = 'main', $belongsTo = null){
        $language = Languages::findOne(['code' => Yii::$app->language]);

        $sql  = 'SELECT * FROM '.Posts::tableName();
        $sql .= ' WHERE (post_type=:type_MENU OR post_type=:type_DROP)';
        $sql .= ' AND status=:status_a';
        if (!is_null($language)){
            $sql .= ' AND language_id=:language_id';
        }

        switch($type){
            case 'main':
                $sql .= ' AND parent_post_id IS NULL';
                break;
            case 'belongsTo':
                $sql .= ' AND parent_post_id=:belongs_to';
                break;
            case 'parents':
                $sql .= ' AND parent_post_id IS NOT NULL';
                break;
        }

        $sql .= ' ORDER BY order_num ASC';

        $cmd = Yii::$app->db->createCommand($sql);
        $cmd->bindValue(':type_MENU', Posts::TYPE_MENU);
        $cmd->bindValue(':type_DROP', Posts::TYPE_DROPDOWN);
        $cmd->bindValue(':status_a', DataProvider::STATUS_ACTIVE);
        if (!is_null($language)){
            $cmd->bindValue(':language_id', $language->id);
        }
        if ($type == 'belongsTo'){
            $cmd->bindParam(':belongs_to', $belongsTo);
        }
        if ($type !== 'parents'){
            return $cmd->queryAll();
        }else{
            return ArrayHelper::map($cmd->queryAll(), 'parent_post_id', 'parent_post_id');
        }
    }
} 