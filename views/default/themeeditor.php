<?php
    use albertborsos\yii2lib\helpers\Glyph;
    use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md-4">
        <?=
            \kartik\grid\GridView::widget([
            'dataProvider' => $filesDataProvider,
            'columns' => [
                [
                    'attribute' => 'fileName',
                    'vAlign' => 'middle',
                    'header' => 'Fájl',
                ],
                [
                    'class'         => 'kartik\grid\ActionColumn',
                    'template'      => '{update}',
                    'updateOptions' => ['title' => 'Módosítás', 'class' => 'btn btn-sm btn-default'],
                    'buttons' => [
                        'update' => function($url, $model, $key){
                                return Html::a(Glyph::icon(Glyph::ICON_EDIT), ['/cms/default/themeeditor?fileName='.$model['fileName']], ['class'=>'btn btn-sm btn-default']);
                            },
                    ],
                ],
            ],
        ])?>
    </div>
    <div class="col-md-8">
        <?= Html::beginForm() ?>
        <?= trntv\aceeditor\Widget::widget([
                'name' => 'file-editor',
                'value' => $fileContent,
                'mode'=>'php', // programing language mode. Default "html"
                'theme'=>'eclipse', // editor theme. Default "github"
                // Editor container options
                'containerOptions' => [
                        'toolbar' => false,
                        'css' => 'wym.css',
                    ],
            ]);
        ?>
        <?= Html::submitButton('Mentés') ?>
        <?= Html::endForm() ?>
    </div>
</div>
