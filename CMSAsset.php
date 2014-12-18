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
            'css/style.css'.$this->time,
            'vendor/Magnific-Popup/magnific-popup.css'.$this->time,
			'vendor/syntaxHighlighter/shCoreEclipse.css'.$this->time,
			'vendor/syntaxHighlighter/shThemeEclipse.css'.$this->time,
        ];

        $this->js = [
            //'js/cms.js'.$this->time,
            'js/images.js'.$this->time,
            'vendor/Magnific-Popup/jquery.magnific-popup.min.js'.$this->time,
            'vendor/syntaxHighlighter/shCore.js'.$this->time,
            'vendor/syntaxHighlighter/shBrushJScript.js'.$this->time,
            'vendor/syntaxHighlighter/shBrushPhp.js'.$this->time,
            'vendor/syntaxHighlighter/shBrushPerl.js'.$this->time,
            'vendor/syntaxHighlighter/shBrushBash.js'.$this->time,
            'vendor/syntaxHighlighter/shBrushSql.js'.$this->time,
            'vendor/syntaxHighlighter/shBrushCss.js'.$this->time,
        ];
    }


} 