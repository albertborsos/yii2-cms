<?php
    use albertborsos\yii2lib\helpers\FileUpload;
    use yii\helpers\Html;

?>
<div class="row">
    <div class="col-md-6">
        <legend>Képek feltöltése ide: <?= $gallery->name ?></legend>
        <?= $form = Html::beginForm('', 'post', ['enctype'=>'multipart/form-data', 'id' => 'gallery-upload']) ?>

        <?= FileUpload::widget([
                'name' => 'filename',
                'url' => ['/cms/galleryphotos/index', 'gallery' => $gallery->id],
                'fieldOptions' => ['multiple' => true, 'accept' => 'image/*'],
                'clientOptions' => [
                    'maxFileSize' => 5000000,
                    'formId' => 'gallery-upload',
                ]
            ]);
        ?>

        <?= Html::endForm() ?>
    </div>
    <div class="col-md-6">
        <?= $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gallery' => $gallery,
        ])?>
    </div>
</div>