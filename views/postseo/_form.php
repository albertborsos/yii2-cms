<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\PostSeo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-seo-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => Yii::$app->urlManager->getBaseUrl().'/cms/postseo/update?id='.$model->id,
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 70]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'meta_robots')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'canonical_post_id')->dropDownList($model->getCanonicalPosts()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
