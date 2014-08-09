<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2lib\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_cms_post_seo".
 *
 * @property string $id
 * @property string $post_id
 * @property string $title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $meta_robots
 * @property string $url
 * @property string $canonical_post_id
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property Posts $post
 * @property Posts $canonicalPost
 */
class PostSeo extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cms_post_seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'canonical_post_id'], 'required'],
            [['post_id', 'canonical_post_id', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['title'], 'string', 'max' => 70],
            [['meta_description'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_robots'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 512],
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
            'post_id' => 'Bejegyzés',
            'title' => 'Title tag',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_robots' => 'Meta Robots',
            'url' => 'URL',
            'canonical_post_id' => 'Canonical Post',
            'created_at' => 'Létrehozva',
            'created_user' => 'Léterhozta',
            'updated_at' => 'Módosítva',
            'updated_user' => 'Módosította',
            'status' => 'Státusz',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCanonicalPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'canonical_post_id']);
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

    public static function getCanonicalPosts($active = true, $forDropDownList = true){
        $sql = 'SELECT * FROM '.Posts::tableName();
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
