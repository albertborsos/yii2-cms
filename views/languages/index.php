<?php

    use yii\helpers\Html;
    use kartik\grid\GridView;
    use albertborsos\yii2cms\components\DataProvider;

    /* @var $this yii\web\View */
    /* @var $searchModel albertborsos\yii2cms\models\LanguagesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="languages-index">
    <legend>Nyelvek áttekintése</legend>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'panel'        => [
                'heading'    => '<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Nyelvek</h3>',
                'type'       => 'default',
                //'before'     => Html::a('<i class="glyphicon glyphicon-plus"></i> Új Nyelv', ['create'], ['class' => 'btn btn-success']),
                'after'      => Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
                'showFooter' => false
            ],
            'export'       => false,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => 'kartik\grid\SerialColumn'],
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
                    'attribute'     => 'code',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return $model['code'];
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
                    'template' => '{update} {delete}',
                    //'dropdown' => true,
                    'updateOptions' => ['title' => 'Módosítás', 'class' => 'btn btn-sm btn-default'],
                    'deleteOptions' => ['title' => 'Törlés', 'class' => 'btn btn-sm btn-default'],
                    'width'         => '90px',
                ],
            ],
        ]); ?>

</div>
