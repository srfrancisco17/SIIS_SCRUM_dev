<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprint-requerimientos-tareas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sprint_id')->textInput() ?>

    <?= $form->field($model, 'requerimiento_id')->textInput() ?>

    <?= $form->field($model, 'tarea_titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tarea_descripcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tiempo_desarrollo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
