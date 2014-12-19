<?php
    use albertborsos\yii2cms\models\Posts;
    use albertborsos\yii2lib\helpers\Date;
    use albertborsos\yii2lib\helpers\Glyph;
    use albertborsos\yii2user\models\Users;
	use kartik\social\FacebookPlugin;
	use yii\helpers\Html;

    /* @var albertborsos\yii2cms\models\Posts $post */
    /* @var string $tags */
?>
<div class="content">
    <div class="header">
        <h1><?= $post->name ?></h1>

        <div class="description">
            <span class="author"><?= Glyph::icon(Glyph::ICON_USER) ?> Szerző: <?= Users::findIdentity($post->created_user)->getFullname() ?></span>
            <span class="date"><?= Glyph::icon(Glyph::ICON_CALENDAR) ?> <?= Date::timestampToDate($post->created_at) ?></span>
                <span class="comment-count">
                <?= Glyph::icon(Glyph::ICON_COMMENT) ?>
                    <a href="<?= Posts::generateUrl($post->id) ?>#disqus_thread">
                        Szólj hozzá!
                    </a>
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