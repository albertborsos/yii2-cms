<?php

    use albertborsos\yii2cms\models\Posts;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Galleries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="galleries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'replace_id')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'order')->dropDownList(DataProvider::items('order')) ?>

    <?= $form->field($model, 'pagesize')->dropDownList(DataProvider::items('pagesize')) ?>

    <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
