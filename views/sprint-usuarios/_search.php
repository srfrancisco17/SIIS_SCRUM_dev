<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SprintUsuariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprint-usuarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sprint_id') ?>

    <?= $form->field($model, 'usuario_id') ?>

    <?= $form->field($model, 'horas_desarrollo') ?>

    <?= $form->field($model, 'observacion') ?>

    <?= $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
