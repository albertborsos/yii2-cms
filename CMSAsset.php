<?php

namespace albertborsos\yii2cms;

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
    ];

    public function init()
    {
        parent::init();
        if (YII_DEBUG){
            $this->time = '?'.time();
        }

        $this->css = [
            //'css/style.css'.$this->time,
        ];

        $this->js = [
            'js/cms.js'.$this->time,
        ];
    }


} 