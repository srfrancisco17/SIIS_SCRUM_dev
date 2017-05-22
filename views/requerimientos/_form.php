<?php
//nuevo
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Usuarios;
use app\models\Departamentos;
use app\models\Comites;
//use kartik\widgets\Select2;
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use kartik\date\DatePicker;
//use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Requerimientos */
/* @var $form yii\widgets\ActiveForm */
?>
            <div class="box-body">
                <div class="requerimientos-form">

                    <?php $form = ActiveForm::begin(); ?>
                    <?php if($model->isNewRecord){
                            
                            $model->fecha_requerimiento = date('Y-m-d');
                          }else{
                              
                          }    
                    ?>
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <?= $form->field($model, 'requerimiento_titulo')->textInput(['maxlength' => true])->label('(*) Titulo Del Requerimiento:') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <?= $form->field($model, 'requerimiento_descripcion')->widget(TinyMce::className(), [
                            'options' => ['rows' => 6],
                            'language' => 'es',
                            'clientOptions' => [
                                'plugins' => [
                                    "advlist autolink lists link charmap print preview anchor",
                                    "searchreplace visualblocks code fullscreen",
                                    "insertdatetime media table contextmenu paste"
                                ],
                                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                            ]
                            ])->label('Descripcion Del Requerimiento:');
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <?= $form->field($model, 'requerimiento_justificacion')->widget(TinyMce::className(), [
                            'options' => ['rows' => 6],
                            'language' => 'es',
                            'clientOptions' => [
                                'plugins' => [
                                    "advlist autolink lists link charmap print preview anchor",
                                    "searchreplace visualblocks code fullscreen",
                                    "insertdatetime media table contextmenu paste"
                                ],
                                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                            ]
                            ])->label('Justificacion Del Requerimiento:');
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <?= $form->field($model, 'observaciones')->widget(TinyMce::className(), [
                            'options' => ['rows' => 6],
                            'language' => 'es',
                            'clientOptions' => [
                                'plugins' => [
                                    "advlist autolink lists link charmap print preview anchor",
                                    "searchreplace visualblocks code fullscreen",
                                    "insertdatetime media table contextmenu paste"
                                ],
                                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                            ]
                            ])->label('Observacion Del Requerimiento:');
                            ?>                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-6">
                            
                            <?= $form->field($model, 'usuario_solicita')->widget(Select2::className(),[
                                'data' => ArrayHelper::map(Usuarios::find()->where(['estado' => 1])->all(), 'usuario_id', 'nombres'),
                                'theme' => Select2::THEME_DEFAULT,
                                'language'=>'es',
                                'options' => ['placeholder'=>'seleccione usuario'],
                                'pluginOptions'=>[
                                    'allowClear'=>true
                                ],
                                ])->label("(*) Usuario Que Solicita:");
                            ?>
                        </div>
                        <div class="col-xs-12 col-lg-6">
                            <?= $form->field($model, 'departamento_solicita')->widget(Select2::className(),[
                                'data' => ArrayHelper::map(Departamentos::find()->all(), 'departamento_id', 'descripcion'),
                                'theme' => Select2::THEME_DEFAULT,
                                'language'=>'es',
                                'options' => ['placeholder'=>'Seleccione Departamento'],
                                'pluginOptions'=>[
                                    'allowClear'=>true
                                ],
                                ])->label('Departamento Que Solicita:');
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-6">
                            <?= $form->field($model, 'fecha_requerimiento')->widget(DatePicker::classname(), [
                            'name' => 'dp_3',
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'size' => 'xs',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd',
                                'startDate' => '2017-01-01'
                            ]
                            ])->label('(*) Fecha Del Requerimiento:'); 
                            ?>
                        </div>
                         <div class="col-xs-12 col-lg-6">

                            <?= $form->field($model, 'estado')->dropDownList(ArrayHelper::map(\app\models\RequerimientosEstados::find()->asArray()->all(), 'reqest_id', 'descripcion'), ['prompt' => 'Seleccione Uno' ])->label('(*) Estado Del Requerimiento');?>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <?= $form->field($model, 'comite_id')->widget(Select2::className(),[
                                'data' => ArrayHelper::map(Comites::find()->where(['estado' => 1])->all(), 'comite_id', 'comite_alias'),
                                'theme' => Select2::THEME_DEFAULT,
                                'language'=>'es',
                                'options' => ['placeholder'=>'Sin Comite'],
                                'pluginOptions'=>[
                                    'allowClear'=>true
                                ],
                                ])->label('Asociar Comite:');
                            ?> 
                        </div>
                    </div>
                </div>
            </div>   
              <div class="box-footer">
                <?= Html::submitButton($model->isNewRecord ? 'Crear Requerimiento' : 'Actualizar Requerimiento', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
              </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>