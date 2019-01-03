<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */


$contrasena_template = '{label} <button type="button" onclick="changePass();" class="btn btn-danger btn-xs">cambiar</button>{input}{hint}{error}';
$contrasena_disabled = true;

if ($model->isNewRecord) {
    $model->tipo_documento = "CC";
    $model->estado = 1;
	
	$contrasena_template = '{label}{input}{hint}{error}';
	$contrasena_disabled = false;
	
}


?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?=($model->isNewRecord) ? "Crear Usuarios": "Actualizar Usuarios"?>
                </h3>    
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'tipo_documento')->dropDownList($model->ListaTipoDocumentos, ['prompt' => 'Seleccione Uno'])->label('(*) Tipo Documento:'); ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'num_documento')->textInput(['maxlength' => 12])->label('(*) Documento:') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'nombres')->textInput(['maxlength' => 30])->label('Nombres:') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'apellidos')->textInput(['maxlength' => 30])->label('Apellidos:') ?>
                    </div>                      
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'descripcion')->textarea(['rows' => '2'])->label('Descripcion:') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'correo')->textInput(['maxlength' => 50])->label('Correo Electrónico:') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => 10])->label('Telefono:') ?>
                    </div>                       
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'contrasena', ['template' => $contrasena_template])->passwordInput(['maxlength' => true, 'disabled' => $contrasena_disabled])->label('(*) Contraseña:') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'departamento')->dropDownList($model->ListaDepartamentos, ['prompt' => 'Seleccione Uno'])->label('Departamento:'); ?>
                    </div>                     
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <?= $form->field($model, 'tipo_usuario')->dropDownList($model->ListaTiposUsuarios, ['prompt' => 'Seleccione Uno'])->label('(*) Tipo Usuario:'); ?>
                    </div>
                    <div class="col-lg-4">
                        <?=
                        $form->field($model, 'estado')->dropDownList(
                                ['1' => 'Activo', '0' => 'Inactivo'], ['prompt' => 'Seleccione Estado']
                        )->label('(*) Estado:')
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?=
                        $form->field($model, 'color')->widget(ColorInput::classname(), [
                            'value' => '#8a2f13',
                            'options' => ['placeholder' => 'Seleccione Color'],
                        ]);
                        ?>
                    </div> 
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear Usuario' : 'Actualizar Usuario', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>

	function changePass(){
		
		$("#usuarios-contrasena").prop("disabled", false); 
		$("#usuarios-contrasena").val(""); 
		
	}

</script>