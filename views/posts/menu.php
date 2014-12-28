<?php

    use albertborsos\yii2cms\models\Languages;
    use albertborsos\yii2cms\models\Posts;
    use albertborsos\yii2lib\helpers\Date;
    use albertborsos\yii2lib\wrappers\Editable;
    use yii\helpers\Html;
    use kartik\grid\GridView;
    use albertborsos\yii2cms\components\DataProvider;

    /* @var $this yii\web\View */
    /* @var $searchModel albertborsos\yii2cms\models\PostsSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="posts-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'panel'        => [
                'heading'    => '<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Bejegyzések</h3>',
                'type'       => 'default',
                'before'     => Html::a('<i class="glyphicon glyphicon-plus"></i> Új Menüpont', ['create?type='.Posts::TYPE_MENU], ['class' => 'btn btn-success'])
                    .' '.Html::a('<i class="glyphicon glyphicon-plus"></i> Új Legördülő Menü', ['create?type='.Posts::TYPE_DROPDOWN], ['class' => 'btn btn-success']),
                'after'      => Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
                'showFooter' => false
            ],
            'export'       => false,
            'filterModel'  => $searchModel,
            'rowOptions' => function($model, $key, $index, $grid){
                if ($model->post_type == Posts::TYPE_DROPDOWN){
                    return ['class' => 'info'];
                }elseif($model->status == DataProvider::STATUS_ACTIVE){
                    return ['class' => 'success'];
                }elseif($model->status == DataProvider::STATUS_DELETED){
                    return ['class' => 'danger'];
                }elseif($model->status == DataProvider::STATUS_INACTIVE){
                    return ['class' => 'warning'];
                }

            },
            'columns'      => [
                [
                    'attribute'       => 'order_num',
                    'hAlign'          => 'center',
                    'vAlign'          => 'middle',
                    'format' => 'raw',
                    'headerOptions'   => ['class' => 'text-center'],
                    'value'           => function($model, $index, $widget){
                            return Editable::select('order_num', $model->id, $model->order_num, $model->order_num, ['/cms/posts/updatebyeditable'], DataProvider::items('pagesize'));
                        },
                    'filter' => false,
                ],
                [
                    'attribute'     => 'language_id',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function($model, $index, $widget){
                            $language  = Languages::findOne(['id' => $model->language_id]);
                            $languages = \yii\helpers\ArrayHelper::map(Languages::findAll(['status' => DataProvider::STATUS_ACTIVE]), 'id', 'name');
                            if (count($languages) > 1){
                                return Editable::select('language_id', $model->id, $model->language_id, $language->name, ['/cms/posts/updatebyeditable'], $languages);
                            }else{
                                return $language->name;
                            }
                        },
                    'filter' => \albertborsos\yii2cms\models\Languages::getLanguages(),
                ],
                [
                    'attribute'       => 'parent_post_id',
                    'hAlign'          => 'center',
                    'vAlign'          => 'middle',
                    'format' => 'raw',
                    'headerOptions'   => ['class' => 'text-center'],
                    'value'           => function($model, $index, $widget){
                            if ($model->post_type == Posts::TYPE_MENU || $model->post_type == Posts::TYPE_DROPDOWN){
                                return Editable::select('parent_post_id', $model->id, $model->parent_post_id, Posts::getPostName($model->parent_post_id), ['/cms/posts/updatebyeditable'], Posts::getSelectParentMenu($model->id, true));
                            }else{
                                return 'Nem módosítható!';
                            }
                        },
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
                    'attribute'     => 'post_type',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return DataProvider::items('post_type', $model['post_type'], false);
                        },
                    'filter'        => false,
                ],
                [
                    'attribute'     => 'commentable',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'format' => 'raw',
                    'value'           => function($model, $index, $widget){
                            return Editable::select('commentable', $model->id, $model->commentable, DataProvider::items('yesno', $model->commentable, false), ['/cms/posts/updatebyeditable'], DataProvider::items('yesno'));
                        },
                    'filter'        => DataProvider::items('yesno'),
                ],
                [
                    'attribute'     => 'date_show',
                    'hAlign'        => 'center',
                    'vAlign'        => 'middle',
                    'headerOptions' => ['class' => 'text-center'],
                    'value'         => function ($model, $index, $widget) {
                            return Date::reformatDateTime($model['date_show']);
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
                    'format' => 'raw',
                    'value'           => function($model, $index, $widget){
                            return Editable::select('status', $model->id, $model->status, DataProvider::items('status', $model->status, false), ['/cms/posts/updatebyeditable'], DataProvider::items('status'));
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
