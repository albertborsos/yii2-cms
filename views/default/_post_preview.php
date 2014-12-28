<?php
    use albertborsos\yii2cms\models\Posts;
    use albertborsos\yii2lib\helpers\Date;
    use albertborsos\yii2user\models\Users;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

    /* @var albertborsos\yii2cms\models\Posts $post */
    /* @var string $tags */
?>
<div class="content">
    <div class="header">
        <h3><?= Html::a($post->name, Posts::generateUrl($post->id))?></h3>

        <div class="description">
            <span class="author"><?= FA::icon(FA::_USER) ?> Szerző: <?= Users::findIdentity($post->created_user)->getFullname() ?></span>
            <span class="date"><?= FA::icon(FA::_CALENDAR) ?> <?= Date::timestampToDate($post->created_at) ?></span>
                <span class="comment-count">
                <?= FA::icon(FA::_COMMENT) ?>
                <?= Html::a('Szólj hozzá!', Posts::generateUrl($post->id).'#disqus_thread')?>
            </span>
        </div>
    </div>
    <div class="tags">
        <p><?= $tags ?></p>
    </div>
    <div class="body">
        <?= $post->content_preview ?>
    </div>
    <div class="body separator">&nbsp;</div>
</div>