<?php
$this->title = 'Perfil';

use app\models\Usuarios;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;



?>
<style>
    .user-row {
        margin-bottom: 14px;
    }

    .user-row:last-child {
        margin-bottom: 0;
    }

    .dropdown-user {
        margin: 13px 0;
        padding: 5px;
        height: 100%;
    }

    .dropdown-user:hover {
        cursor: pointer;
    }

    .table-user-information > tbody > tr {
        border-top: 1px solid rgb(221, 221, 221);
    }

    .table-user-information > tbody > tr:first-child {
        border-top: 0;
    }

    .table-user-information > tbody > tr > td {
        border-top: 0;
    }
    .toppad{
        margin-top:20px;
    }    
</style>
<script>
    function editarDatos(parameter){
        
        
        if (parameter == 1) {
            
            $("#usuarios-nombres").attr("readonly", false);
            $("#usuarios-apellidos").attr("readonly", false);
            $("#usuarios-correo").attr("readonly", false);
            $("#usuarios-telefono").attr("readonly", false);
            
        }else if (parameter == 2){
            
            $("#usuarios-contrasena").attr("readonly", false);
            $("#usuarios-password_repeat").attr("readonly", false);
            
            $("#usuarios-contrasena").val('')
            $("#usuarios-password_repeat").val('')
            
        } 
        
       
       
       $('#boton1').prop('disabled', false);
       
       

        
    }
</script>
<?php $form = ActiveForm::begin(); ?>
<div class="row">   
            <div class="col-md-9 col-md-offset-1">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Perfil De Usuario</h3>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-3 col-lg-3 " align="center">
                        <!--<img alt="User Pic" src="http://babyinfoforyou.com/wp-content/uploads/2014/10/avatar-300x300.png" class="img-circle img-responsive">--> 
                        <?= Html::img('@web/img/icono-cdo.png', ['alt' => 'User Pic']) ?>
                    </div>
                    <div class=" col-md-9 col-lg-9 "> 
                      <table class="table table-user-information">
                        <tbody>
                          <tr>
                            <td>Nombres:</td>
                            <td><?= $form->field($model, 'nombres')->textInput(['maxlength' => 30, 'readonly' => true])->label(FALSE) ?></td>
                          </tr>
                          <tr>
                            <td>Apellidos:</td>
                            <td><?= $form->field($model, 'apellidos')->textInput(['maxlength' => 30, 'readonly' => true])->label(FALSE) ?></td>
                          </tr>
                          <tr>
                            <td>Correo Electronico:</td>
                            <td><?= $form->field($model, 'correo')->textInput(['maxlength' => 50, 'readonly' => true])->label(FALSE) ?></td>
                          </tr>
                          <tr>
                            <td>Telefono:</td>
                            <td><?= $form->field($model, 'telefono')->textInput(['maxlength' => 10, 'readonly' => true])->label(FALSE) ?></td>
                          </tr>
                          <tr>
                          <td>
                              Cambiar Contraseña:
                              <button type="button" class="btn btn-danger btn-xs" onclick="editarDatos(2)">cambiar</button>  
                          </td>
                            <td><?= $form->field($model, 'contrasena')->passwordInput(['readonly' => true, 'placeholder' => 'Escriba Nueva Contraseña'])->label(FALSE) ?></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td><?= $form->field($model, 'password_repeat')->passwordInput(['readonly' => true, 'value' => $model->contrasena, 'placeholder' => 'Repita Contraseña'])->label(FALSE) ?></td>
                          </tr>
                          <tr>
                            <td>Tipo De Usuario:</td>
                            <td><?= $model->tipoUsuario->descripcion ?></td>
                          </tr>
                          <tr>
                            <td>Departamento:</td>
                            <td><?= $model->departamento0->descripcion ?></a></td>
                          </tr>
                            <td>Color:</td>
                            <td>
                                <?=$form->field($model, 'color')->widget(ColorInput::classname(), [
                                    'disabled' => true,
                                    'options' => ['placeholder' => 'Select color ...'],
                                ])->label(FALSE); ?>       
                            </td>

                          </tr>

                        </tbody>
                      </table>
                    <!--
                      <a href="#" class="btn btn-primary">My Sales Performance</a>
                      <a href="#" class="btn btn-primary">Team Sales Performance</a>
                    -->
                    </div>
                  </div>
                </div>
                     <div class="panel-footer">
                            <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                            <span class="pull-right">
                                <?= Html::submitButton('Guardar Datos', ['id'=>'boton1', 'disabled' => 'disabled', 'class' => 'btn btn-primary']) ?>
                                <a href="#" id="editar" onclick="editarDatos(1)" data-original-title="Editar Mis Datos" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                                <!--<a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>-->
                            </span>
                        </div>

              </div>
            </div>

</div>
<?php ActiveForm::end(); ?>

