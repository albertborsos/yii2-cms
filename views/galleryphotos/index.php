<?php

    use albertborsos\yii2lib\wrappers\Editable;
    use yii\helpers\Html;
    use kartik\grid\GridView;
    use albertborsos\yii2cms\components\DataProvider;

    /* @var $this yii\web\View */
    /* @var $searchModel albertborsos\yii2cms\models\GalleryPhotosSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="gallery-photos-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'panel'        => [
                'heading'    => '<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Gallery Photos</h3>',
                'type'       => 'default',
                //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Új Gallery Photos', ['create'], ['class' => 'btn btn-success']),
                'after'      => Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index', 'gallery' => $gallery->id], ['class' => 'btn btn-info']),
                'showFooter' => false
            ],
            'export'       => false,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => 'kartik\grid\SerialColumn'],
//                [
//                    'attribute'     => 'gallery_id',
//                    'hAlign'        => 'center',
//                    'vAlign'        => 'middle',
//                    'headerOptions' => ['class' => 'text-center'],
//                    'value'         => function ($model, $index, $widget) {
//                            return $model['gallery_id'];
//                        },
//                ],
//                [
//                    'attribute'     => 'filename',
//                    'hAlign'        => 'center',
//                    'vAlign'        => 'middle',
//                    'headerOptions' => ['class' => 'text-center'],
//                    'value'         => function ($model, $index, $widget) {
//                            return $model['filename'];
//                        },
//                ],
                [
                    'header'        => 'Előnézet',
                    'headerOptions' => ['class' => 'text-center'],
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'format'        => 'raw',
                    'value'         => function($model, $index, $widget){
                            return Html::img($model->getUrlFull(true), ['width' => 120]);
                        }
                ],
                [
                    'attribute'     => 'title',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'format'        => 'raw',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return Editable::input('title', $model['id'], $model['title'], ['updatebyeditable']);
                        },
                ],
                [
                    'attribute'     => 'description',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'format'        => 'html',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return $model['description'];
                        },
                ],
                [
                    'attribute'     => 'updated_at',
                    //'header'        => 'Utolsó módosítás',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'format'        => 'raw',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return \albertborsos\yii2lib\db\ActiveRecord::showLastModifiedInfo($model);
                        },
                ],
                [
                    'attribute'      => 'status',
                    'hAlign'=>'center',
                    'vAlign'=>'middle',
                    'headerOptions'  => ['class' => 'text-center'],
                    'format' => 'raw',
                    'value'          => function($model, $index, $widget){
                            return Editable::select('status', $model['id'], $model['status'], DataProvider::items('status', $model['status'], false), ['updatebyeditable'], DataProvider::items('status'));
                        },
                    'filter' => DataProvider::items('status'),
                ],
                [
                    'class'         => 'kartik\grid\ActionColumn',
                    'template'      => '{update} {delete}',
                    'header'        => '',
                    //'dropdown' => true,
                    'viewOptions'   => ['title' => 'Áttekintés', 'class' => 'btn btn-sm btn-default'],
                    'updateOptions' => ['title' => 'Módosítás', 'class' => 'btn btn-sm btn-default'],
                    'deleteOptions' => ['title' => 'Törlés', 'class' => 'btn btn-sm btn-default'],
                    'width'         => '45px',
                ],
            ],
        ]); ?>

</div>
