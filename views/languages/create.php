<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Languages */

$this->title = 'Create Languages';
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-6">
<div class="languages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
