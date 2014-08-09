<?php

    use yii\helpers\Html;
    use kartik\grid\GridView;
    use albertborsos\yii2cms\components\DataProvider;

    /* @var $this yii\web\View */
    /* @var $searchModel albertborsos\yii2cms\models\PostsSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Posts';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'panel'        => [
                'heading'    => '<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Bejegyzések</h3>',
                'type'       => 'default',
                'before'     => Html::a('<i class="glyphicon glyphicon-plus"></i> Új Menüpont', ['create?type=MENU'], ['class' => 'btn btn-success'])
                    .' '.Html::a('<i class="glyphicon glyphicon-plus"></i> Új Blog bejegyzés', ['create?type=BLOG'], ['class' => 'btn btn-success']),
                'after'      => Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
                'showFooter' => false
            ],
            'export'       => false,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute'     => 'language_id',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return $model->language->name;
                        },
                ],
                /*[
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'order_num',
                    'pageSummary' => 'Page Total',
                    'vAlign'=>'middle',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],
                    'editableOptions'=>['header'=>'sorrend', 'size'=>'md']
                ],*/
                [
                    'attribute'     => 'post_type',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return DataProvider::items('post_type', $model['post_type'], false);
                        },
                    'filter'        => DataProvider::items('post_type'),
                ],
                [
                    'attribute'     => 'name',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return $model['name'];
                        },
                ],
                [
                    'attribute'     => 'commentable',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return DataProvider::items('yesno', $model['commentable'], false);
                        },
                    'filter'        => DataProvider::items('yesno'),
                ],
                [
                    'attribute'     => 'date_show',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return $model['date_show'];
                        },
                ],
                [
                    'attribute'     => 'status',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return DataProvider::items('status', $model['status'], false);
                        },
                    'filter'        => DataProvider::items('status'),
                ],
                [
                    'class'         => 'kartik\grid\ActionColumn',
                    'template'      => '{update} {delete}',
                    //'dropdown' => true,
                    'viewOptions'   => ['title' => 'Áttekintés', 'class' => 'btn btn-sm btn-default', 'visible' => false],
                    'updateOptions' => ['title' => 'Módosítás', 'class' => 'btn btn-sm btn-default'],
                    'deleteOptions' => ['title' => 'Törlés', 'class' => 'btn btn-sm btn-default'],
                    'width'         => '90px',
                ],
            ],
        ]); ?>

</div>
