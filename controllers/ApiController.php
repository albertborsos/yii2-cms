<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2lib\helpers\Seo;
use Yii;

class ApiController extends \yii\rest\Controller
{

    public function actionMigrateUp(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        $content = DataProvider::migrateUp();
        Seo::noIndex();
        Yii::$app->cache->flush();
        print $content;
    }
}
