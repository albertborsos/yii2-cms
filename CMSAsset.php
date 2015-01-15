<?php

namespace albertborsos\yii2cms;

use albertborsos\yii2lib\helpers\S;
use yii\web\AssetBundle;
use Yii;

class CMSAsset extends AssetBundle
{
    public $time;
    public $sourcePath = '@vendor/albertborsos/yii2-cms/assets/';

    public $css;
    public $js;

    public $depends = [
        'yii\web\JqueryAsset',
        '\rmrevin\yii\fontawesome\AssetBundle',
    ];

    public function init()
    {
        parent::init();
        if (YII_DEBUG){
            $this->time = '?'.time();
        }

        $this->css = [
            //'css/style.css'.$this->time,
            'css/style.css'.$this->time,
            'vendor/Magnific-Popup/magnific-popup.css'.$this->time,
        ];

        $this->js = [
            'js/cms.js'.$this->time,
            'js/images.js'.$this->time,
            'vendor/Magnific-Popup/jquery.magnific-popup.min.js'.$this->time,
        ];
    }


} 