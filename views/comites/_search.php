<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ComitesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comites-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'comite_id') ?>

    <?= $form->field($model, 'comite_alias') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'hora_desde') ?>

    <?= $form->field($model, 'hora_hasta') ?>

    <?php // echo $form->field($model, 'dirigido_por') ?>

    <?php // echo $form->field($model, 'lugar') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
