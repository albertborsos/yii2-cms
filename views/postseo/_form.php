<?php

    use albertborsos\yii2cms\models\Posts;
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

    <?= $form->field($model, 'title')->textInput(['maxlength' => 70, 'placeholder' => 'Alapértelmezetten a Főcím']) ?>
    
    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255, 'placeholder' => 'Alapértelmezetten az Előnézet vagy a Főcím']) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 100, 'placeholder' => 'Alapértelmezetten a Cimkék']) ?>

    <?= $form->field($model, 'meta_robots')->dropDownList(DataProvider::items('meta-robots')) ?>

    <?= $form->field($model, 'url')->textarea(['maxlength' => '512', 'rows' => '4', 'placeholder' => Posts::generateUrl($model->post_id)]) ?>

    <?= $form->field($model, 'canonical_post_id')->dropDownList($model->getCanonicalPosts()) ?>

    <?= $form->field($model, 'url')->textInput(['value' => Posts::generateUrl($model->post_id), 'disabled' => 'disabled']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
