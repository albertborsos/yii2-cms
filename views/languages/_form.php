<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Languages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="languages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

      <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
