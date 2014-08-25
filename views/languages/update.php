<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Languages */

?>
<div class="row">
<div class="col-md-4">
<div class="languages-update">

    <legend>Nyelv módosítása</legend>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
    <div class="col-md-8">
        <?= $this->render('index.php', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ])  ?>
    </div>
</div>
</div>
