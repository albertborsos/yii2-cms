<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $searchModel albertborsos\yii2cms\models\GalleryPhotosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gallery Photos';
?>
<div class="gallery-photos-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Gallery Photos</h3>',
            'type' => 'default',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Új Gallery Photos', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'export' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

          [
              'attribute'      => 'gallery_id',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return $model['gallery_id'];
                                  },
          ],
          [
              'attribute'      => 'filename',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return $model['filename'];
                                  },
          ],
          [
              'attribute'      => 'title',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return $model['title'];
                                  },
          ],
          [
              'attribute'      => 'description',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                      return $model['description'];
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
                'template' => '{update} {delete}',
                //'dropdown' => true,
                'viewOptions'=>['title'=>'Áttekintés', 'class'=>'btn btn-sm btn-default'],
                'updateOptions'=>['title'=>'Módosítás', 'class'=>'btn btn-sm btn-default'],
                'deleteOptions'=>['title'=>'Törlés', 'class'=>'btn btn-sm btn-default'],
                'width' => '80px',
            ],
        ],
    ]); ?>

</div>
