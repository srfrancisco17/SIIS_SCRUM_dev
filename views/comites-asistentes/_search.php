<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ComitesAsistentesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comites-asistentes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'comite_id') ?>

    <?= $form->field($model, 'usuario_id') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'sw_responsable') ?>

    <?= $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
