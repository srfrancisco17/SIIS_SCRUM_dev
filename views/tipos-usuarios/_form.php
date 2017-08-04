<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TiposUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>




<div class="tipos-usuarios-form">

    <?php $form = ActiveForm::begin([
        'id' => 'TipoUsuarios-form',
          'enableAjaxValidation' => true,
          'enableClientScript' => true,
          'enableClientValidation' => true,
        ]); 
    ?>
    <?php if($model->isNewRecord){
            $model->estado = 1;
          }else{

          }    
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'tipo_usuario_id')->textInput() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'estado')->dropDownList(
                ['1' => 'Activo', '0' => 'Inactivo'],
                ['prompt'=>'Seleccione Estado']    
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <?= Html::submitButton($model->isNewRecord ? 'Crear Tipo De Usuarios' : 'Actualizar Tipo De Usuarios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>  

    <?php ActiveForm::end(); ?>
    <?php
    $this->registerJs('
    // obtener la id del formulario y establecer el manejador de eventos
        $("form#TipoUsuarios-form").on("beforeSubmit", function(e) {
            var form = $(this);
            $.post(
                form.attr("action")+"&submit=true",
                form.serialize()
            )
            .done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:"#TipoUsuarios-grid"});
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
