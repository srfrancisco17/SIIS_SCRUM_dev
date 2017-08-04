<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprint-requerimientos-tareas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tarea_id')->textInput() ?>

    <?= $form->field($model, 'sprint_id')->textInput() ?>

    <?= $form->field($model, 'requerimiento_id')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
