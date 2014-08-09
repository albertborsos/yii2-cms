<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Posts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'language_id')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'post_type')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'content_preview')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content_main')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'order_num')->textInput() ?>

    <?= $form->field($model, 'commentable')->textInput(['maxlength' => 1]) ?>

    <?= $form->field($model, 'date_show')->textInput() ?>

      <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
