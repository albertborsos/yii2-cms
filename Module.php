<?php

namespace albertborsos\yii2cms;

use albertborsos\yii2cms\components\ContactForm;
use albertborsos\yii2cms\models\Galleries;
use albertborsos\yii2cms\models\Posts;
use albertborsos\yii2lib\helpers\S;
use Yii;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'albertborsos\yii2cms\controllers';
    public $name = 'Tartalomkezelő';

    /**
     * Merge these contents with the default replaceableContents
     *
     *   'extraReplaceableContents' => [
     *      ['\albertborsos\yii2newsletter\models\Lists', 'insertForms'],
     *   ],
     */
    public $extraReplaceableContents = [];

    /**
     * Posts::insertform() and Galleries::insertGallery()
     */
    private $_replaceableContents    = [];

    /**
     * override default ContactForm with other Form
     *
     *  'forms' => [
     *      '[#contactUs#]' => '\frontend\models\ContactForm',
     *  ],
     */
    public $forms     = [];

    /**
     * override defauélt subscribe form with other Form
     *
     *  'listForms' => [
     *      //'1' => '\frontend\models\ContactForm',
     *   ]
     */
    public $listForms = [];

    /**
     * override defauélt subscribe form with other Form
     *
     *  'listForms' => [
     *      //'1' => '\frontend\models\ContactForm',
     *   ]
     */
    public $campaignForms = [];

    public $enableSyntaxHighlighter = false;

    /**
     * Module specific urlManager
     * @param $app
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->id . '/?' => $this->id . '/default/index',
            $this->id . '/<id:\d+>' => $this->id . '/default/view',
            $this->id . '/<action:\w+>/?' => $this->id . '/default/<action>',
            $this->id . '/<controller:\w+>/<action:\w+>' => $this->id . '/<controller>/<action>',
        ], false);
    }

    public function init()
    {
        parent::init();
        $i18n = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@vendor/albertborsos/yii2-cms/messages',
                'forceTranslation' => true
            ];
        Yii::$app->i18n->translations['cms'] = $i18n;
        // custom initialization code goes here
        $view = \Yii::$app->view;
        CMSAsset::register($view);

        if($this->enableSyntaxHighlighter){
            SyntaxHighlighterAsset::register($view);
        }

        $this->setReplaceableContents();
        $this->setForms();
    }

    private function setForms(){
        $defaultForms = [
            '[#contactUs#]' => ContactForm::className(),
        ];

        $this->forms = ArrayHelper::merge($defaultForms, $this->forms);
    }

    /**
     * merges the CMS default replaceable items with $extrareplaceableContents
     */
    private function setReplaceableContents(){
        $this->_replaceableContents = [
            // [className, method]
            [Galleries::className(), 'insertGallery'],
            [Posts::className(), 'insertForms'],
        ];

        $this->_replaceableContents = ArrayHelper::merge($this->_replaceableContents, $this->extraReplaceableContents);
    }

    private function getReplaceableContents(){
        return $this->_replaceableContents;
    }

    public function replaceItems($content){
        $items = $this->getReplaceableContents();
        foreach($items as $item){
            $class = S::get($item, '0');
            $method = S::get($item, 1);

            $content = $class::$method($content);
        }
        return $content;
    }




}
