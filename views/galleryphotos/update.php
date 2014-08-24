<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\GalleryPhotos */

?>
<div class="row">
<div class="col-md-6">
<div class="gallery-photos-update">

    <legend><?= Html::encode($this->title) ?></legend>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
