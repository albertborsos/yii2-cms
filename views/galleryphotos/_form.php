<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2cms\components\DataProvider;
use zyx\widgets\Redactor;

    /* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\GalleryPhotos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gallery-photos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->widget(Redactor::className(), [
                'model' => $model,
                'attribute' => 'content_preview',
                'options' => \albertborsos\yii2lib\helpers\Widgets::redactorOptions(),
            ]) ?>

      <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
