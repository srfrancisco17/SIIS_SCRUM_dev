<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosTareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requerimientos-tareas-form">

    
    <?php
    
    if($model->isNewRecord){
        $model->horas_desarrollo = 1;
    }
    
    $form = ActiveForm::begin([
        'id' => 'tareas-form',
        'enableAjaxValidation' => true,
        'enableClientScript' => true,
        'enableClientValidation' => true,
    ]); 
    ?>
    
    
    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'tarea_titulo')->textInput(['maxlength' => true])->label('(*) Titulo') ?>
        </div>
        <div class="col-lg-3">
                <?= $form->field($model, 'horas_desarrollo')->textInput(['type' => 'number'])->label('Horas') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
                <?= $form->field($model, 'tarea_descripcion')->textarea(['rows' => 6])->label('Descripcion') ?>  
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <!-- <?= $form->field($model, 'requerimiento_id')->textInput() ?> -->


    <!-- <?= $form->field($model, 'ultimo_estado')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'horas_desarrollo')->textInput(['maxlength'=>2]) ?> -->

    <!-- <?= $form->field($model, 'fecha_terminado')->textInput() ?> -->


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
                    
                    $("#modal").modal("hide");
                    $.pjax.reload({container:"#grid-requerimientos_tareas"});
                    
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
