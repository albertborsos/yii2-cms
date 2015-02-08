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

        $this->css = [
            //'css/style.css',
            'css/style.css',
            'vendor/Magnific-Popup/magnific-popup.css',
        ];

        $this->js = [
            'js/cms.js',
            'js/images.js',
            'vendor/Magnific-Popup/jquery.magnific-popup.min.js',
        ];
    }


} 