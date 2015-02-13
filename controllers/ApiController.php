<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\DataProvider;
use Yii;

class ApiController extends \yii\rest\Controller
{
	public function actionMigrateUp(){
		DataProvider::migrateUp();
		Yii::$app->end();
	}

}
