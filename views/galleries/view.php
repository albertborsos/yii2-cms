<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Galleries */

$this->title = $model->name;
?>
<div class="galleries-view">

    <legend><?= Html::encode($this->title) ?></legend>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'replace_id',
            'name',
            'created_at',
            'created_user',
            'updated_at',
            'updated_user',
            'status',
        ],
    ]) ?>

</div>
