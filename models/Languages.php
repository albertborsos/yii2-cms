<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2historizer\Historizer;
use albertborsos\yii2lib\db\ActiveRecord;
use Yii;
use yii\bootstrap\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_cms_languages".
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property Posts[] $posts
 */
class Languages extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cms_languages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 50],
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
            'code' => 'Nyelv kódja',
            'name' => 'Megnevezés',
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
    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['language_id' => 'id']);
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
            Historizer::createArchive($this);
            $this->setOwnerAndTime();
            return true;
        }else{
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()){
            Historizer::createArchive($this);
            return true;
        }else{
            return false;
        }
    }

    public static function getLanguages($active = true, $forDropDownList = true){
        $sql = 'SELECT * FROM '.self::tableName();
        if ($active){
            $sql .= ' WHERE status=:status_active';
            $cmd = Yii::$app->db->createCommand($sql);
            $cmd->bindValue(':status_active', DataProvider::STATUS_ACTIVE);
        }else{
            $cmd = Yii::$app->db->createCommand($sql);
        }
        if ($forDropDownList){
            return ArrayHelper::map($cmd->queryAll(), 'id', 'name');
        }else{
            return $cmd->queryAll();
        }
    }

}
