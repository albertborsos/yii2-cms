<?php
	use kartik\social\FacebookPlugin;

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