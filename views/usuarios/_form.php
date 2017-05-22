<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Usuarios</h3>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                  <div class="row">
                      <div class="col-lg-6">
                         <?= $form->field($model, 'num_documento')->textInput(['maxlength' => 12])->label('(*) Documento:') ?>
                      </div>
                      <div class="col-lg-6">
                          <?= $form->field($model, 'tipo_documento')->dropDownList($model->ListaTipoDocumentos, ['prompt' => 'Seleccione Uno' ])->label('(*) Tipo Documento:');?>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                       <?= $form->field($model, 'nombres')->textInput(['maxlength' => 30])->label('(*) Nombres:') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'apellidos')->textInput(['maxlength' => 30])->label('(*) Apellidos:') ?>
                    </div>                      
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'descripcion')->textarea(['rows' => '2'])->label('Descripcion:') ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                       <?= $form->field($model, 'correo')->textInput(['maxlength' => 50])->label('(*) Correo Electrónico:') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => 10])->label('(*) Telefono:') ?>
                    </div>                       
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                       <?= $form->field($model, 'contrasena')->passwordInput(['maxlength' => true])->label('(*) Contraseña:') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'departamento')->dropDownList($model->ListaDepartamentos, ['prompt' => 'Seleccione Uno' ])->label('(*) Departamento:');?>
                    </div>                     
                  </div>
                  <div class="row">
                    <div class="col-lg-4">
                       <?= $form->field($model, 'tipo_usuario')->dropDownList($model->ListaTiposUsuarios, ['prompt' => 'Seleccione Uno' ])->label('(*) Tipo Usuario:');?>
                    </div>
                    <div class="col-lg-4">
                    <?php if($model->isNewRecord){
                            $model->estado  = 1;
                          }    
                    ?>
                    <?= $form->field($model, 'estado')->dropDownList(
                        ['1' => 'Activo', '0' => 'Inactivo'],
                        ['prompt'=>'Seleccione Estado']    
                    )->label('(*) Estado:') ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>
                    </div> 
                  </div>
  
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="form-group">
                     <?= Html::submitButton($model->isNewRecord ? 'Crear Usuario' : 'Actualizar Usuario', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
              </div>
            
          </div>
    </div>
  

        

        

</div>
    <?php ActiveForm::end(); ?>
