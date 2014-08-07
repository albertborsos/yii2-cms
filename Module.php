<?php

namespace albertborsos\yii2cms;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'albertborsos\yii2cms\controllers';
    public $name = 'Tartalomkezelő';

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
        // custom initialization code goes here
    }
}
