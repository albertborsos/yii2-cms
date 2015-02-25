<?php
/**
 * @var yii\web\View $this
 * @var albertborsos\yii2cms\models\UserDetails $model
 * @var SetNewPasswordForm $new_pwd_model
 */
?>
<div class="row">
    <div class="col-sm-6">
        <legend>Alapadatok módosítása</legend>
        <?php
            include(__DIR__ . '/../userdetails/_form.php');
        ?>
    </div>
    <div class="col-sm-6">
        <legend>Jelszómódosítás</legend>
        <?php
            print Yii::$app->controller->renderPartial('_new_pwd_form', [
                    'model' => $new_pwd_model,
                ]);
        ?>
    </div>
</div>