<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ComitesAsistentes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comites-asistentes-form">
    
<?php $form = ActiveForm::begin([
    'id' => 'ComitesAsistentes-form',
      'enableAjaxValidation' => true,
      'enableClientScript' => true,
      'enableClientValidation' => true,
    ]); 
?>

   
    
<?= $form->field($model, 'comite_id')->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
    
<?= $form->field($model, 'usuario_id')->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-6">
                <p aling=center style="font-size:15px">
                    <?php
                        echo '<B>USUARIO: </B>'.$model->usuario->nombres.' '.$model->usuario->apellidos;
                    ?>
                </p>
            </div>
            <div class="col-lg-6">
                <p aling=center style="font-size:15px" >
                    <?php
                        echo '<B>DEPARTAMENTO: </B>'.$model->usuario->departamento0->descripcion;
                    ?>
                </p>
            </div>  
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'estado')->dropDownList(
                    ['1' => 'Activo', '0' => 'Inactivo'],
                    ['prompt'=>'Seleccione Estado']    
                )->label('Estado:') ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'sw_responsable')->dropDownList(
                    ['1' => 'Si'],
                    ['prompt'=>'No']    
                )->label('Responsable:') ?>
            </div> 
            <div class="col-lg-12">
                <?= $form->field($model, 'observacion')->textarea(['maxlength' => true])->label('Observacion:') ?>
            </div>
        </div>        
    </div>
    <div class="panel-footer">    
        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
    $this->registerJs('
    // obtener la id del formulario y establecer el manejador de eventos
        $("form#ComitesAsistentes-form").on("beforeSubmit", function(e) {
            var form = $(this);
            $.post(
                form.attr("action")+"&submit=true",
                form.serialize()
            )
            .done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:"#ComitesAsistentes-grid"});
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
