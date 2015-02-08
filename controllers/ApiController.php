<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2lib\helpers\S;
use Yii;

class ApiController extends \yii\rest\Controller
{
	public function actionMigrateUp(){
		S::migrateUp();
		Yii::$app->end();
	}

}
