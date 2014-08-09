<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2lib\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tbl_cms_posts".
 *
 * @property string $id
 * @property string $language_id
 * @property string $post_type
 * @property string $name
 * @property string $content_preview
 * @property string $content_main
 * @property integer $order_num
 * @property string $commentable
 * @property string $date_show
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property TblCmsPostSeo[] $tblCmsPostSeos
 * @property TblCmsLanguages $language
 */
class Posts extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cms_posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id'], 'required'],
            [['language_id', 'order_num', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['content_preview', 'content_main'], 'string'],
            [['date_show'], 'safe'],
            [['post_type'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 160],
            [['commentable', 'status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language_id' => 'Nyelv',
            'post_type' => 'Típus',
            'name' => 'Főcím',
            'content_preview' => 'Előnézet',
            'content_main' => 'Tartalom',
            'order_num' => 'Sorrend',
            'commentable' => 'Hozzá lehet szólni?',
            'date_show' => 'Megjelenés ideje',
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
    public function getTblCmsPostSeos()
    {
        return $this->hasMany(TblCmsPostSeo::className(), ['canonical_post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(TblCmsLanguages::className(), ['id' => 'language_id']);
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
