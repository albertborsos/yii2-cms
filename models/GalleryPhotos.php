<?php

namespace albertborsos\yii2cms\models;

use albertborsos\yii2lib\db\ActiveRecord;
use albertborsos\yii2lib\helpers\S;
use Exception;
use Imagine\Image\ImagineInterface;
use Yii;
use yii\image\drivers\Image_GD;
use yii\image\drivers\Kohana_Image_GD;
use yii\image\ImageDriver;
use yii\imagine\Image;
use yii\web\UploadedFile;

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

    public $pathBase;
    public $pathFile;
    public $pathWatermark;
    public $urlBase;
    public $pathSavedFile;

    public function init()
    {
        parent::init();
        $this->pathWatermark = Yii::$app->getBasePath().'/web/images/watermark-TL.png';
        $this->pathBase      = Yii::$app->getBasePath()."/web/uploads/images";
        $this->urlBase       = Yii::$app->urlManager->getBaseUrl()."/uploads/images";
    }

    public function getPathFull($thumbnail = false){
        if (!$thumbnail){
            return $this->pathBase.'/'.$this->filename;
        }else{
            return $this->pathBase.'/s/'.$this->filename;
        }
    }

    public function getUrlFull($thumbnail = false){
        if (!$thumbnail){
            return $this->urlBase.'/'.$this->filename;
        }else{
            return $this->urlBase.'/s/'.$this->filename;
        }
    }

    public function getUrlDelete(){
        return Yii::$app->urlManager->createAbsoluteUrl(['/cms/gallaryphotos/delete', 'id' => $this->id]);
    }

    public function deleteFiles(){
        $files[] = $this->getPathFull(true);
        $files[] = $this->getPathFull();
        foreach($files as $file){
            if (file_exists($file)){
                unlink($file);
            }
        }
        return true;
    }

    public function saveUploadedFile(UploadedFile $uploaded){
        $pathFull = $this->getPathFull();
        if (!is_dir($this->pathBase)) {
            mkdir($this->pathBase, 0777, true);
            chmod($this->pathBase, 0777);
        }
        if ($uploaded->saveAs($pathFull)){
            chmod($pathFull, 0777);
            if (!$this->save()){
                $this->throwNewException('Nem sikerült menteni az adatbázisba!');
            }
        }
    }

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

    public function setExtension(UploadedFile $uploaded){
        switch ($uploaded->type) {
            case 'image/gif':
                $extension = '.gif';
                break;
            case 'image/jpeg':
            case 'image/x-citrix-jpeg':
            case 'image/pjpeg':
                $extension = '.jpg';
                break;
            case 'image/png':
            case 'image/x-png':
            case 'image/x-citrix-png':
                $extension = '.png';
                break;
            default:
                throw new Exception('Nem megfelelő fájltípus!');
                break;
        }
        return $extension;
    }

    public function generateUniqueName($extension = null){
        $mit    = array("á","é","í","ó","ö","ő","ú","ü","ű","Á","É","Í","Ó","Ö","Ő","Ú","Ü","Ű");
        $mire   = array("a","e","i","o","o","o","u","u","u","a","e","i","o","o","o","u","u","u");

        $this->filename = time().'-'.$this->filename;

        $ext_in_filename = explode('.', $this->filename);
        $ext_in_filename = '.'.$ext_in_filename[count($ext_in_filename)-1];
        $this->filename  = str_replace($ext_in_filename, '', $this->filename);

        $this->filename  = str_replace($mit, $mire, $this->filename);

        $this->filename  = mb_strtolower($this->filename);

        $this->filename  = preg_replace('@[\s!:;_\?=\\\+\*/%&#\.]+@', '-', $this->filename);
        $this->filename  = preg_replace('/\-+/', '-', $this->filename);

        if (is_null($extension)){
            $this->filename .= $ext_in_filename;
        }else{
            $this->filename .= $extension;
        }
        $this->filename = trim($this->filename, '-');
    }

    public function savePhoto($file, $width, $watermark = true, $thumbnail = false){
        $image = \yii\image\drivers\Image::factory($file);
        if ($image->width > $width){
            $image->resize($width, null);
        }
        if ($watermark){
            //$image->watermark($this->watermark);
        }
        if ($thumbnail){
            $pathToSave = $this->getPathFull(true);
        }else{
            $pathToSave = $this->getPathFull();
        }
        return $image->save($pathToSave, 90);
    }
}
