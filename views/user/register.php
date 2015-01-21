<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var albertborsos\yii2cms\models\Users $model
 * @var ActiveForm $form
 */
?>
<div class="row">
    <div class="col-md-offset-3 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Regisztráció</h3></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'lastName') ?>
                <?= $form->field($model, 'firstName') ?>
                <?= $form->field($model, 'email')->input('email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="btn-block">
                    <?= Html::submitButton('Regisztráció', ['class' => 'btn btn-primary btn-block', 'id' => 'registerform-submit']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="alert alert-info text-justify">
                    <p><b>Mi fog történni?</b> A megadott e-mailcímre kiküldünk egy aktiváló levelet. Ebben a levélben kattins a linkre, utána be tudsz lépni az oldalra!</p>
                </div>
            </div>
            <!-- panel-body -->
        </div>
        <!-- panel panel-default -->
        <?= Html::a('Van már fiókod? Jelentkezz be!', Yii::$app->urlManager->createUrl(['/cms/user/login']), ['class' => 'btn btn-block']) ?>
    </div>
</div>
