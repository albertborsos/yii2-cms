<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Galleries */

$this->title = 'Galleries módosítása: ' . ' ' . $model->name;
?>
<div class="row">
<div class="col-md-6">
<div class="galleries-update">

    <legend><?= Html::encode($this->title) ?></legend>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
