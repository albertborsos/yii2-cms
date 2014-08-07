<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2lib\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
