<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Usuarios;
use app\models\Departamentos;
use app\models\Comites;
//use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\grid\GridView;


use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

date_default_timezone_set('America/Bogota');
$estado_field_requerimiento = TRUE;


if($model->isNewRecord || Yii::$app->user->identity->tipo_usuario == Usuarios::USUARIO_SCRUM_MASTER){
    
    $model->fecha_requerimiento = date('Y-m-d');
    $estado_field_requerimiento = FALSE;
}


$this->registerCss("
    
    .panel-default > .panel-heading-custom {
        background: #5A6D82; color: #fff;
    }

");


?>
<!-- NUEVOS CAMBIOS 09/01/2018 -->
<?php $form = ActiveForm::begin(); ?>
    
<div class="panel panel-default">
    <div class="panel-heading panel-heading-custom">
        <h3 class="panel-title">
            <b>HISTORIA DE USUARIO:</b>
        </h3>
    </div>
    <div class="panel-body">
  
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'requerimiento_titulo')->textInput(['maxlength' => true, 'disabled' => $estado_field_requerimiento])->label('* Titulo del requerimiento:') ?>
            </div>
            <div class="col-lg-4">
                
                <?= $form->field($model, 'usuario_solicita')->widget(Select2::className(),[
                    'data' => ArrayHelper::map(Usuarios::find()->where(['estado' => 1])->all(), 'usuario_id', 'nombreCompleto'),
                    'theme' => Select2::THEME_DEFAULT,
                    'language'=>'es',
                    'options' => ['placeholder'=>'Seleccione usuario'],
                    'disabled' => $estado_field_requerimiento,
                    'pluginOptions'=>[
                        'allowClear'=>true
                    ],
                    ])->label("* Usuario solicitante:");
                ?>
              
            </div>
            <div class="col-lg-4">
               
                <?= $form->field($model, 'departamento_solicita')->widget(Select2::className(),[
                    'data' => ArrayHelper::map(Departamentos::find()->all(), 'departamento_id', 'descripcion'),
                    'theme' => Select2::THEME_DEFAULT,
                    'language'=>'es',
                    'disabled' => $estado_field_requerimiento,
                    'options' => ['placeholder'=>'Seleccione Departamento'],
                    'pluginOptions'=>[
                        'allowClear'=>true
                    ],
                    ])->label('Departamento solicitante:');
                ?>

            </div>   
        </div>      
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'fecha_requerimiento')->widget(DatePicker::classname(), [
                'name' => 'dp_3',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'size' => 'xs',
                'disabled' => $estado_field_requerimiento,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'startDate' => '2017-01-01'
                ]
                ])->label('* Fecha requerimiento:'); 
                ?>
            </div>
            <div class="col-lg-4">
                <?php

                    if ($model->isNewRecord){
                        $arreglo = array('0'=>'Inactivo', '1' => 'Activo');
                        echo $form->field($model, 'estado')->dropDownList($arreglo)->label('* Estado requerimiento');
                    }else{
                        echo $form->field($model, 'estado')->dropDownList(ArrayHelper::map(\app\models\EstadosReqSpr::find()->where(['sw_requerimiento'=>'1'])->asArray()->all(), 'req_spr_id', 'descripcion'), ['prompt' => 'Seleccione Estado' ,'disabled' => $estado_field_requerimiento])->label('* Estado requerimiento');
                    }
                ?>
                
            </div>
            <div class="col-lg-4">
                
                <?= $form->field($model, 'comite_id')->widget(Select2::className(),[
                    'data' => ArrayHelper::map(Comites::find()->where(['estado' => 1])->all(), 'comite_id', 'comite_alias'),
                    'theme' => Select2::THEME_DEFAULT,
                    'language'=>'es',
                    'disabled' => $estado_field_requerimiento,
                    'options' => ['placeholder'=>'Sin Comite'],
                    'pluginOptions'=>[
                        'allowClear'=>true
                    ],
                    ])->label('Asociar Comite:');
                ?> 
                
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                  <?= $form->field($model, 'requerimiento_descripcion')->textarea(['rows' => '3', 'readonly' => $estado_field_requerimiento])->label('* Como(Rol):') ?>
            </div>
            <div class="col-lg-3">
                  <?= $form->field($model, 'requerimiento_funcionalidad')->textarea(['rows' => '3', 'readonly' => $estado_field_requerimiento])->label('* Necesito(Funcionalidad):') ?>
            </div>
            <div class="col-lg-3">
                  <?= $form->field($model, 'requerimiento_justificacion')->textarea(['rows' => '3', 'readonly' => $estado_field_requerimiento])->label('* Para(Finalidad):') ?>
            </div>
            <div class="col-lg-3">
                  <?= $form->field($model, 'observaciones')->textarea(['rows' => '3', 'readonly' => $estado_field_requerimiento]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'divulgacion')->dropDownList([ 0 => 'No requiere', 1 => 'Informativo',  2 => 'Capacitacion formal'], ['prompt' => 'Seleccione plan' ])->label('Plan de divulgación:') ?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Crear Requerimiento' : 'Actualizar HU', ['id'=>'boton1' , 'onclick' =>'validarBoton()', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
 <?php ActiveForm::end(); ?>

<?php
    if( !$model->isNewRecord && !empty($sprint_id) ){

?>
<div class="row">
    <div class="col-lg-12">
        <?php Pjax::begin(['id' => 'grid_tareas']) ?>
        <?= GridView::widget([
            'id' => 'grid-requerimientos_tareas',
            'dataProvider' => $RT_dataProvider,
            'filterModel' => $RT_searchModel,
            'panel' => [
                'heading' => '<b>CRITERIOS DE ACEPTACION (TAREAS):</b>',
                'headingOptions' => ['class'=>'panel-heading panel-heading-custom'],
                'type' => GridView::TYPE_DEFAULT,
            ],
            'toolbar' => [
                'content' => 
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Tarea', '#', [
                    'class' => 'btn btn-success botones',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-url' => Url::to(['create-requerimientos-tareas', 'sprint_id' => $sprint_id ,'requerimiento_id' => $model->requerimiento_id]),
                    'data-pjax' => '0',
                    'data-opcion' => 'modal1-create'
                ]),
                
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'tarea_titulo',
                'tarea_descripcion:ntext',
                [
                    'label' => 'Horas',
                    'attribute' => 'horas_desarrollo',
                    'contentOptions' => ['style' => 'width:10px;'],
                ],
                [
                    'class'=>'kartik\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => 
                        function ($url, $model, $key) {
 
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                        'class' => 'botones',
                                        'title' => Yii::t('yii', 'Actualizar'),
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal',
                                        'data-url' => Url::to(['update-requerimientos-tareas', 'tarea_id' => $model->tarea_id, 'sprint_id' => $model->sprintRequerimientosTareas->sprint_id]),
                                        'data-pjax' => '0',
                                        'data-opcion' => 'modal1-update'
                            ]);
                        },    
                                
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'onclick' => "
                                    if (confirm('Esta seguro de eliminar este registro?')) {
                                         $.ajax('".Url::to(['delete-requerimientos-tareas', 'tarea_id' => $model->tarea_id, 'sprint_id' => $model->sprintRequerimientosTareas->sprint_id])."', {
                                            type: 'POST'
                                        }).done(function(data) {
                                            $.pjax.reload({container: '#grid_tareas'});
                                        }).fail( function() {
                                            alert('ERROR AL INTENTAR ELIMINAR UNA TAREA!');
                                        });
                                    }
                                    return false;
                                ",
                            ]);
                        },               
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
     </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <?php Pjax::begin(['id' => 'grid_procesos_involucrados']) ?>
        <?= GridView::widget([
            'dataProvider' => $PI_dataProvider,
            //'filterModel' => $PI_searchModel,
            'panel' => [
                'heading' => '<b>PROCESOS INVOLUCRADOS:</b>',
                'headingOptions' => ['class'=>'panel-heading panel-heading-custom'],
                'type' => GridView::TYPE_DEFAULT,
            ],
            'toolbar' => [
                'content' => 
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar proceso', '#', [
                    'class' => 'btn btn-success botones',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-url' => Url::to(['create-procesos-involucrados', 'requerimiento_id' => $model->requerimiento_id]),
                    'data-pjax' => '0',
                    'data-opcion' => 'modal2-create'
                ]),
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'descripcion',
                [
                    'class'=>'kartik\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                        'class' => 'botones',
                                        'title' => Yii::t('yii', 'Actualizar'),
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal',
                                        'data-url' => Url::to(['update-procesos-involucrados', 'id' => $model->id]),
                                        'data-pjax' => '0',
                                        'data-opcion' => 'modal2-update'
                            ]);
                        },     
                                
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'onclick' => "
                                    if (confirm('Esta seguro de eliminar este registro?')) {
                                        $.ajax('".Url::to(['delete-procesos-involucrados', 'id' => $model->id, 'requerimiento_id' => $model->requerimiento_id])."', {
                                            type: 'POST'
                                        }).done(function(data) {
                                            $.pjax.reload({container: '#grid_procesos_involucrados'});
                                        });
                                    }
                                    return false;
                                ",
                            ]);
                        }, 
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <div class="col-lg-6">
        
        <?php Pjax::begin(['id' => 'grid_perfiles_usuarios']) ?>
       
        <?= GridView::widget([
            'dataProvider' => $PUI_dataProvider,
            //'filterModel' => $PUI_searchModel,
            'panel' => [
                'heading' => '<b>PERFILES DE USUARIOS IMPACTADOS:</b>',
                'headingOptions' => ['class'=>'panel-heading panel-heading-custom'],
                'type' => GridView::TYPE_DEFAULT,
            ],
            'toolbar' => [
                'content' => 
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar perfil', '#', [
                    'id' => 'btn_perfiles_usuario',
                    'class' => 'btn btn-success botones',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-url' => Url::to(['create-perfiles-impactados', 'requerimiento_id' => $model->requerimiento_id]),
                    'data-pjax' => '0',
                    //'data-opcion' => '1',
                    'data-opcion' => 'modal3-create', 
                ]),
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'descripcion',
                [
                    'class'=>'kartik\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                        //'id' => 'btn_perfiles_usuario',
                                        'class' => 'botones',
                                        'title' => Yii::t('yii', 'Actualizar'),
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal',
                                        'data-url' => Url::to(['update-perfiles-impactados', 'id' => $model->id]),
                                        'data-pjax' => '0',
                                        'data-opcion' => 'modal3-update'
                            ]);
                        },                            
      
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'onclick' => "
                                    if (confirm('Esta seguro de eliminar este registro?')) {
                                        $.ajax('".Url::to(['delete-perfiles-impactados', 'id' => $model->id, 'requerimiento_id' => $model->requerimiento_id])."', {
                                            type: 'POST'
                                        }).done(function(data) {
                                            $.pjax.reload({container: '#grid_perfiles_usuarios'});
                                        });
                                    }
                                    return false;
                                ",
                            ]);
                        },        
                                       
                    ]
                ],
            ],
        ]); ?>
      <?php Pjax::end(); ?>
    </div>
</div>
<?php
    
    /*
     * MODAL
     */
    
    $this->registerJs("
        
        $(document).on('click', '.botones', (function() {   
            var texto_titulo = '';
            

            var propiedades_modal = $(this).data('opcion').split('-');


            if (propiedades_modal[0] === 'modal1'){
            

                if (propiedades_modal[1] === 'create'){
                
                    texto_titulo = 'CREAR TAREA';
                    $('#modal').find('.modal-header').css('background-color','#008C4D');
                
                }else if (propiedades_modal[1] === 'update'){
                
                    $('#modal').find('.modal-header').css('background-color','#367EA8');
                    texto_titulo = 'ACTUALIZAR TAREA';
                
                }
                

            }else if(propiedades_modal[0] === 'modal2'){
            
            
                if (propiedades_modal[1] === 'create'){
                
                    texto_titulo = 'AGREGAR PROCESO INVOLUCRADO';
                    $('#modal').find('.modal-header').css('background-color','#008C4D');
                
                }else if (propiedades_modal[1] === 'update'){
                
                    $('#modal').find('.modal-header').css('background-color','#367EA8');
                    texto_titulo = 'MODIFICAR PROCESO INVOLUCRADO';
                
                }



            }else if(propiedades_modal[0] === 'modal3'){
            
                
                
                if (propiedades_modal[1] === 'create'){
                
                    texto_titulo = 'AGREGAR EL PERFIL DEL USUARIO QUE IMPACTA';
                    $('#modal').find('.modal-header').css('background-color','#008C4D');
                
                }else if (propiedades_modal[1] === 'update'){
                
                    $('#modal').find('.modal-header').css('background-color','#367EA8');
                    texto_titulo = 'MODIFICAR EL PERFIL DEL USUARIO QUE IMPACTA';
                
                }
            

            }

            $('#titulo_perfiles_usuario').text(texto_titulo);

            $.get(
                $(this).data('url'),
                function (data) {

                    $('.modal-body').html(data);
                    $('#modal').modal();
                }
            );

        }));
    
    ");
?>
<?php

    Modal::begin([
        'id' => 'modal',
        'header' => '<h5 style="font-weight: bold; color:white;" id="titulo_perfiles_usuario" class="modal-title"></h5>',
    ]);
    echo "<div class='well'></div>";
    Modal::end();
    
    }
?>
