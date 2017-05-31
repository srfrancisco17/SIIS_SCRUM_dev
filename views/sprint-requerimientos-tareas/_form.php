<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprint-requerimientos-tareas-form">

        <?php $form = ActiveForm::begin([
        'id' => 'tareas-form',
        'enableAjaxValidation' => true,
        'enableClientScript' => true,
        'enableClientValidation' => true,
        ]); 
    ?>

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
    
    <?php
        $this->registerJs('
        // obtener la id del formulario y establecer el manejador de eventos
            $("form#tareas-form").on("beforeSubmit", function(e) {
                var form = $(this);
                $.post(
                    form.attr("action")+"&submit=true",
                    form.serialize()
                )
                .done(function(result) {
                    form.parent().html(result.message);
                    $.pjax.reload({container:"#tareas-grid"});
                });
                return false;
            }).on("submit", function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            });
        ');
    ?>

</div>
