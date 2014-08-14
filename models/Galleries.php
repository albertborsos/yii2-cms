<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2lib\db\ActiveRecord;
use dosamigos\gallery\Gallery;
use Yii;
use yii\base\Model;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "tbl_cms_galleries".
 *
 * @property integer $id
 * @property string $replace_id
 * @property string $name
 * @property string $order
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property GalleryPhotos[] $tblCmsGalleryPhotos
 */
class Galleries extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cms_galleries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['replace_id'], 'string', 'max' => 50],
            [['replace_id'], 'unique'],
            [['order'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'replace_id' => 'Beillesztő kód',
            'name' => 'Név',
            'order' => 'Sorrend',
            'created_at' => 'Létrehozva',
            'created_user' => 'Létrehozta',
            'updated_at' => 'Módosítva',
            'updated_user' => 'Módosította',
            'status' => 'Státusz',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCmsGalleryPhotos()
    {
        return $this->hasMany(GalleryPhotos::className(), ['gallery_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()){
            return true;
        }else{
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            $this->setOwnerAndTime();
            return true;
        }else{
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()){
            return true;
        }else{
            return false;
        }
    }

    public function getReplaceID(){
        if (is_null($this->replace_id)){
            $this->replace_id = '[#gallery-'.$this->getNextID().'#]';
        }
        return $this->replace_id;
    }

    public static function insertGallery($content){
        $galleries = Galleries::findAll(['status' => DataProvider::STATUS_ACTIVE]);
        foreach($galleries as $gallery){
            if (strpos($content, $gallery->replace_id) !== false){
                $content = str_replace($gallery->replace_id, $gallery->generate(), $content);
            }
        }
        return $content;
    }

    public function generate(){
        return \albertborsos\yii2gallery\Gallery::widget([
            'id' => 'gallery-'.$this->id,
            'header' => $this->name,
            'galleryId' => $this->id,
        ]);
    }

}
