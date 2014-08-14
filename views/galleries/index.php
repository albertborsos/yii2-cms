<?php

    use albertborsos\yii2lib\helpers\Glyph;
    use albertborsos\yii2lib\wrappers\Editable;
    use yii\helpers\Html;
use kartik\grid\GridView;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $searchModel albertborsos\yii2cms\models\GalleriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Galleries';
?>
<div class="galleries-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Galleries</h3>',
            'type' => 'default',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Új Galleries', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'export' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

          [
              'attribute'      => 'replace_id',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'headerOptions'  => ['class' => 'text-center'],
              'format' => 'raw',
              'value'          => function($model, $index, $widget){
                                      return Html::input('text', $model['replace_id'], $model['replace_id'], ['class' => 'form-control', 'readonly' => 'readonly']);
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
              'attribute'      => 'order',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'format'=>'raw',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                    return Editable::select('order', $model['id'], $model['order'], DataProvider::items('order', $model['order'], false), ['updatebyeditable'], DataProvider::items('order'));
                                  },
          ],
          [
              'attribute'      => 'status',
              'hAlign'=>'center',
              'vAlign'=>'middle',
              'format'=>'raw',
              'headerOptions'  => ['class' => 'text-center'],
              'value'          => function($model, $index, $widget){
                                    return Editable::select('status', $model['id'], $model['status'], DataProvider::items('status', $model['status'], false), ['updatebyeditable'], DataProvider::items('status'));
                                  },
              'filter' => DataProvider::items('status'),
          ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{upload} {update} {delete}',
                //'dropdown' => true,
                'buttons' => [
                    'upload' => function($url, $model, $key){
                            return Html::a(Glyph::icon(Glyph::ICON_PLUS), ['/cms/galleryphotos/index?gallery='.$model['id']], ['class'=>'btn btn-sm btn-default']);
                        },
                ],
                'viewOptions'=>['title'=>'Áttekintés', 'class'=>'btn btn-sm btn-default'],
                'updateOptions'=>['title'=>'Módosítás', 'class'=>'btn btn-sm btn-default'],
                'deleteOptions'=>['title'=>'Törlés', 'class'=>'btn btn-sm btn-default'],
                'width' => '130px',
            ],
        ],
    ]); ?>

</div>
