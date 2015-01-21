<?php
    use albertborsos\yii2cms\models\Posts;
    use albertborsos\yii2lib\helpers\Date;
    use albertborsos\yii2cms\models\Users;
	use kartik\social\FacebookPlugin;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

    /* @var albertborsos\yii2cms\models\Posts $post */
    /* @var string $tags */
?>
<div class="content">
    <div class="header">
        <h1><?= $post->name ?></h1>

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
        <i><?= $post->content_preview ?></i>
        <?= $post->content_main ?>
    </div>
	<div id="social-buttons" class="alert alert-warning">
		<?= FacebookPlugin::widget(['type' => FacebookPlugin::LIKE]) ?>
	</div>
    <div class="body separator">&nbsp;</div>
	<?php if($post->commentable){?>
	<div id="comments">
		<?= \kartik\social\Disqus::widget(); ?>
	</div>
	<?php }?>
</div>