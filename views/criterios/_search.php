<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CriteriosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="criterios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'criterio_id') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'descripcion_abreviada') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'orden') ?>

    <?php // echo $form->field($model, 'valor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
