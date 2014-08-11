<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Posts */

    switch($model->post_type){
        case 'BLOG':
            $this->title = 'Új blog bejegyzés létrehozása';
            break;
        case 'MENU':
            $this->title = 'Új menüpont létrehozása';
            break;
        default:
            $this->title = 'Új bejegyzés létrehozása';
            break;
    }
?>
<div class="row">
<div class="col-md-8">
<div class="posts-create">

    <legend><?= Html::encode($this->title) ?></legend>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
    ]) ?>

</div>
</div>
</div>

