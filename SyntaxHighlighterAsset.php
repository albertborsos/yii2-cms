<?php

namespace albertborsos\yii2cms;

use albertborsos\yii2lib\helpers\S;
use yii\web\AssetBundle;
use Yii;

class SyntaxHighlighterAsset extends AssetBundle
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

        $this->css = [
			'vendor/syntaxHighlighter/shCoreEclipse.css',
			'vendor/syntaxHighlighter/shThemeEclipse.css',
        ];

        $this->js = [
            'vendor/syntaxHighlighter/shCore.js',
            'vendor/syntaxHighlighter/shBrushJScript.js',
            'vendor/syntaxHighlighter/shBrushPhp.js',
            'vendor/syntaxHighlighter/shBrushPerl.js',
            'vendor/syntaxHighlighter/shBrushBash.js',
            'vendor/syntaxHighlighter/shBrushSql.js',
            'vendor/syntaxHighlighter/shBrushCss.js',
        ];
    }


} 