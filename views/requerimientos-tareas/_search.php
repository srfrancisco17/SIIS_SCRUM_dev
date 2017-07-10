<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosTareasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requerimientos-tareas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tarea_id') ?>

    <?= $form->field($model, 'requerimiento_id') ?>

    <?= $form->field($model, 'tarea_titulo') ?>

    <?= $form->field($model, 'tarea_descripcion') ?>

    <?= $form->field($model, 'ultimo_estado') ?>

    <?php // echo $form->field($model, 'tiempo_desarrollo') ?>

    <?php // echo $form->field($model, 'fecha_terminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
