<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */
/* @var $form yii\widgets\ActiveForm */
?>
<?php   
    if($model->isNewRecord){
        $model->tiempo_desarrollo = 0;
    }
        
    $form = ActiveForm::begin([
        'id' => 'tareas-form',
        'enableAjaxValidation' => true,
        'enableClientScript' => true,
        'enableClientValidation' => true,
    ]); 
?>
    <div class="box box-solid box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?= $model->isNewRecord ? 'Crear Nueva Tarea' : 'Actualizar Tarea' ?>
            </h3>
        </div>
        <div class="box-body">

        <div class="row">
            <div class="col-lg-9">
                <?= $form->field($model, 'tarea_titulo')->textInput(['maxlength' => true])->label('(*) Titulo') ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'tiempo_desarrollo')->textInput(['type' => 'number'])->label('Horas') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'tarea_descripcion')->textarea(['rows' => 6])->label('Descripcion') ?>  
            </div>
        </div>

        </div>
        <div class="panel-footer">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
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