<?php

namespace albertborsos\yii2cms\components;

use albertborsos\yii2cms\models\Posts;
use albertborsos\yii2lib\helpers\S;
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
            case 'post_type':
                $array = [
                    'BLOG' => 'Blog bejegyzés',
                    'MENU' => 'Menüpont',
                    'DROP' => 'Legördülő Menü',
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

    /**
     * legfeljebb 3 szinten működik
     *
     * 1.) Lekérdezek minden menüpontot aminek nincs őse
     * 2.) Lekérdezem azokat a menüpontokat, amiknek van őse és elmentem az őseit a $parents tömbbe
     * 3.) Kiíratom az első szintet
     *      - ha valamelyiknek van gyermeke, akkor kiíratom a 2.szintet
     * 4.) Lekérdezem ehhez az őshöz tartozó gyermekeket
     *
     * @return array
     */
    public static function generateMenuItems(){
        $menuItems    = [];
        $printedItems = [];

        $firstLevelMenus = self::getFirstLevelMenus();
        $parents = self::getParentMenuIDs();

        foreach($firstLevelMenus as $menu){
            if (is_null($menu['parent_post_id']) && array_key_exists($menu['id'], $parents) && !array_key_exists($menu['id'], $printedItems)){
                // ha nincs szülője és ő szülő, akkor a hozzá tartozó menüpontokat kell kiíratni
                $children = self::getSecondLevelMenus($menu['id']);
                $items = [];
                foreach($children as $child){
                    $items2 = [];
                    if (!is_null($child['parent_post_id']) && array_key_exists($child['id'], $parents) && !array_key_exists($child['id'], $printedItems)){
                        $children2 = self::getSecondLevelMenus($child['id']);
                        foreach($children2 as $child2){
                            $items2[] = [
                                'label' => $child2['name'],
                                'url' => Posts::generateUrl($child2['id']),
                            ];
                        }
                    }
                    if (empty($items2)){
                        $items[] = [
                            'label' => $child['name'],
                            'url' => Posts::generateUrl($child['id']),
                        ];
                    }else{
                        $items[] = [
                            'label' => $child['name'],
                            'url' => '#',
                            'items' => $items2,
                        ];
                    }
                }
                $menuItems[] = [
                    'label' => $menu['name'],
                    'url' => '#',
                    'linkOptions' => [
                        'class' => 'dropdown-toggle',
                        'data-toggle' => 'dropdown',
                    ],
                    'items' => $items,
                ];
                $printedItems[$menu['id']] = 'printed';
            }else{
                // ha nincs hozzá tartozó elem, akkor csak kiíratom
                $menuItems[] = [
                    'label' => $menu['name'],
                    'url' => Posts::generateUrl($menu['id']),
                ];
            }
        }
        return $menuItems;
    }

    public static function getFirstLevelMenus(){
        // lekérdezem a szülő menüpontokat
        $sql  = 'SELECT * FROM '.Posts::tableName();
        $sql .= ' WHERE (post_type=:type_MENU OR post_type=:type_DROP)';
        $sql .= ' AND status=:status_a';
        $sql .= ' AND parent_post_id IS NULL';
        $sql .= ' ORDER BY order_num ASC';

        $cmd = Yii::$app->db->createCommand($sql);
        $cmd->bindValue(':type_MENU', 'MENU');
        $cmd->bindValue(':type_DROP', 'DROP');
        $cmd->bindValue(':status_a', DataProvider::STATUS_ACTIVE);

        return $cmd->queryAll();
    }

    public static function getSecondLevelMenus($belongsTo){
        // lekérdezem a szülő menüpontokat
        $sql  = 'SELECT * FROM '.Posts::tableName();
        $sql .= ' WHERE (post_type=:type_MENU OR post_type=:type_DROP)';
        $sql .= ' AND status=:status_a';
        $sql .= ' AND parent_post_id=:belongs_to';
        $sql .= ' ORDER BY order_num ASC';

        $cmd = Yii::$app->db->createCommand($sql);
        $cmd->bindValue(':type_MENU', 'MENU');
        $cmd->bindValue(':type_DROP', 'DROP');
        $cmd->bindValue(':status_a', DataProvider::STATUS_ACTIVE);
        $cmd->bindParam(':belongs_to', $belongsTo);

        return $cmd->queryAll();
    }

    public static function getParentMenuIDs(){
        // lekérdezem a szülő menüpontokat
        $sql  = 'SELECT * FROM '.Posts::tableName();
        $sql .= ' WHERE (post_type=:type_MENU OR post_type=:type_DROP)';
        $sql .= ' AND status=:status_a';
        $sql .= ' AND parent_post_id IS NOT NULL';
        $sql .= ' ORDER BY order_num ASC';

        $cmd = Yii::$app->db->createCommand($sql);
        $cmd->bindValue(':type_MENU', 'MENU');
        $cmd->bindValue(':type_DROP', 'DROP');
        $cmd->bindValue(':status_a', DataProvider::STATUS_ACTIVE);

        return ArrayHelper::map($cmd->queryAll(), 'parent_post_id', 'parent_post_id');
    }
} 