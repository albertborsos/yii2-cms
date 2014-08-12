<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2lib\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tbl_cms_gallery_photos".
 *
 * @property integer $id
 * @property integer $gallery_id
 * @property string $filename
 * @property string $title
 * @property string $description
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property Galleries $gallery
 */
class GalleryPhotos extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cms_gallery_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_id', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['description'], 'string'],
            [['filename', 'title'], 'string', 'max' => 255],
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
            'gallery_id' => 'Galéria',
            'filename' => 'Fájlnév',
            'title' => 'Cím',
            'description' => 'Leírás',
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
    public function getGallery()
    {
        return $this->hasOne(Galleries::className(), ['id' => 'gallery_id']);
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

}
