<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Languages */

$this->title = 'Új nyelv létrehozása';
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
