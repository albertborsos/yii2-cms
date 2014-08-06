<?php

namespace vendor\albertborsos\yii2cms\models;

use Yii;

/**
 * This is the model class for table "tbl_cms_languages".
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $created_at
 * @property integer $created_user
 * @property string $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property TblCmsPosts[] $tblCmsPosts
 */
class Languages extends \albertborsos\yii2lib\db\ActiveRecord
{
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
            [['created_at', 'updated_at'], 'safe'],
            [['created_user', 'updated_user'], 'integer'],
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
}
