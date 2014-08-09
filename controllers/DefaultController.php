<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2lib\web\Controller;

class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name          = 'Alap';
        $this->layout        = '//center';
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
}
