<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RequerimientosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requerimientos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'requerimiento_id') ?>

    <?= $form->field($model, 'comite_id') ?>

    <?= $form->field($model, 'requerimiento_titulo') ?>

    <?= $form->field($model, 'requerimiento_descripcion') ?>

    <?= $form->field($model, 'requerimiento_justificacion') ?>

    <?php // echo $form->field($model, 'usuario_solicita') ?>

    <?php // echo $form->field($model, 'departamento_solicita') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'fecha_requerimiento') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
