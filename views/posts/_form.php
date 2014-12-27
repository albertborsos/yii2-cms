<?php

    use albertborsos\yii2cms\models\Languages;
    use albertborsos\yii2cms\models\Posts;
    use albertborsos\yii2tagger\models\Tags;
    use kartik\widgets\DateTimePicker;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use albertborsos\yii2cms\components\DataProvider;
    use vova07\imperavi\Widget as Redactor;

    /* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Posts */
/* @var $tags string */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'language_id')->dropDownList(Languages::getLanguages()) ?>

    <?php
        if(!array_key_exists($model->post_type, DataProvider::items('post_type'))){
            print $form->field($model, 'post_type')->dropDownList(DataProvider::items('post_type'), ['prompt' => 'Válassz típust!']);
        }
    ?>

    <?= $form->field($model, 'parent_post_id')->dropDownList(Posts::getSelectParentMenu($model->id), ['prompt' => 'Válassz! (nem kötelező)']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 160]) ?>

    <?php if($model->post_type !== 'DROP'): ?>

    <?= Tags::Widget('tags', $tags) ?>

    <?php if($model->post_type == 'BLOG'):?>
    <?= $form->field($model, 'content_preview')->widget(Redactor::className(), [
        'model' => $model,
        'attribute' => 'content_preview',
        'settings' => \albertborsos\yii2lib\helpers\Widgets::redactorOptions(),
    ]) ?>
    <?php endif ?>

    <?= $form->field($model, 'content_main')->widget(Redactor::className(), [
        'model' => $model,
        'attribute' => 'content_main',
        'settings' => \albertborsos\yii2lib\helpers\Widgets::redactorOptions(),
    ]) ?>

    <?= $form->field($model, 'commentable')->dropDownList(DataProvider::items('yesno')) ?>

    <?= $form->field($model, 'date_show')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Válassz megjelenési időpontot'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]) ?>

    <?php endif ?>

    <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
