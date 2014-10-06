<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ContactForm */
?>
<div class="well">
    <?php $form = ActiveForm::begin(['id' => 'contact-form', 'options' => [
            'class' => 'form-horizontal'
         ],
         'fieldConfig' => [
             'template' => '{label}<div class="col-sm-9">{input}</div><div class="col-sm-9 col-sm-offset-3">{error}</div>',
             'labelOptions' => ['class' => 'col-sm-3 control-label'],
         ]]); ?>
        <?= $form->field($model, 'nameFirst') ?>
        <?= $form->field($model, 'nameLast') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'subject') ?>
        <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
        <?= $form->field($model, 'verifyCode')->textInput(['placeholder' => 'eredmény számmal vagy betűvel'])->label($model->getVerifyCodePlaceholder()) ?>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
