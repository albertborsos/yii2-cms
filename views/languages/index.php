<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $searchModel albertborsos\yii2cms\models\LanguagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Languages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="languages-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Languages</h3>',
            'type'=>'success',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Új Languages', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'export' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

          [
              'attribute'      => 'code',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return $model['code'];
                                  },
          ],
          [
              'attribute'      => 'name',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return $model['name'];
                                  },
          ],
          [
              'attribute'      => 'status',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return DataProvider::items('status', $model['status'], false);
                                  },
              'filter' => DataProvider::items('status'),
          ],
            [
                'class' => 'kartik\grid\ActionColumn',
                //'dropdown' => true,
                'urlCreator' => function($action, $model, $key, $index) { return '#'; },
                'viewOptions'=>['title'=>'Áttekintés', 'class'=>'btn btn-sm btn-default'],
                'updateOptions'=>['title'=>'Módosítás', 'class'=>'btn btn-sm btn-default'],
                'deleteOptions'=>['title'=>'Törlés', 'class'=>'btn btn-sm btn-default'],
                'width' => '130px',
            ],
        ],
    ]); ?>

</div>
