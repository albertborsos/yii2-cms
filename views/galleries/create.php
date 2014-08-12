<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Galleries */

$this->title = 'Új Galleries létrehozása';
?>
<div class="row">
<div class="col-md-6">
<div class="galleries-create">

    <legend><?= Html::encode($this->title) ?></legend>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
