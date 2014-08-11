<?php

    use albertborsos\yii2cms\components\DataProvider;
    use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2cms\models\Posts */
/* @var $tags string */

$this->title = DataProvider::items('post_type', $model->post_type, false).' módosítása: ' . ' ' . $model->name;;
?>
<div class="row">
<div class="col-md-8">
<div class="posts-update">

    <legend><?= Html::encode($this->title) ?></legend>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
    ]) ?>

</div>
</div>
    <div class="col-md-4">
        <div class="post-seo-create well">

            <legend>SEO beállítások</legend>

            <?= $this->render('../postseo/_form', [
                'model' => $model->seo,
            ]) ?>

        </div>
    </div>
</div>
