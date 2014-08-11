<?php

    use albertborsos\yii2lib\helpers\Widgets;
    use albertborsos\yii2tagger\models\Tags;
    use kartik\widgets\DateTimePicker;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2cms\components\DataProvider;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Posts */
/* @var $tags string */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'language_id')->dropDownList(\albertborsos\yii2cms\models\Languages::getLanguages()) ?>

    <?php switch($model->post_type){
        case 'BLOG':
        case 'MENU':
            break;
        default:
            print $form->field($model, 'post_type')->dropDownList(DataProvider::items('post_type'), ['prompt' => 'Válassz típust!']);
            break;
    }?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 160]) ?>

    <?= Tags::Widget('tags', $tags) ?>

    <?php if($model->post_type == 'BLOG'):?>
    <?= $form->field($model, 'content_preview')->widget('\yii\imperavi\Widget', [
        'model' => $model,
        'attribute' => 'content_preview',
        'options' => \albertborsos\yii2lib\helpers\Widgets::redactorOptions(),
    ]) ?>
    <?php endif ?>

    <?= $form->field($model, 'content_main')->widget('\yii\imperavi\Widget', [
        'model' => $model,
        'attribute' => 'content_preview',
        'options' => \albertborsos\yii2lib\helpers\Widgets::redactorOptions(),
    ]) ?>

    <?= $form->field($model, 'commentable')->dropDownList(DataProvider::items('yesno')) ?>

    <?= $form->field($model, 'date_show')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Válassz megjelenési időpontot'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
