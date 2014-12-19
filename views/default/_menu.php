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
    <div class="body">
        <i><?= $post->content_preview ?></i>
        <?= $post->content_main ?>
    </div>
    <div class="body separator">&nbsp;</div>
	<div id="social-buttons" class="alert alert-warning">
		<?= FacebookPlugin::widget(['type' => FacebookPlugin::LIKE]) ?>
	</div>
	<?php if($post->commentable){?>
		<div id="comments">
			<?= \kartik\social\Disqus::widget(); ?>
		</div>
	<?php }?>
</div>