<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2lib\db\ActiveRecord;
use Yii;

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
 * @property TblCmsPosts[] $tblCmsPosts
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
    public function getTblCmsPosts()
    {
        return $this->hasMany(TblCmsPosts::className(), ['language_id' => 'id']);
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
