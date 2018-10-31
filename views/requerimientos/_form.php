<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Usuarios;
use app\models\Departamentos;
use app\models\Comites;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\grid\GridView;

use kartik\file\FileInput;

use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

date_default_timezone_set('America/Bogota');
$estado_field_requerimiento = TRUE;


if($model->isNewRecord){
    
	$model->fecha_requerimiento = date('Y-m-d');
	
	if (Yii::$app->user->identity->tipo_usuario == Usuarios::USUARIO_SCRUM_MASTER){
		
		$estado_field_requerimiento = FALSE;
	}

}

$this->registerCss("
    .panel-default > .panel-heading-custom {
        background: #5A6D82; color: #fff;
    }
");

$btn_print_html = "";

if ( !empty($sprint_id) && !empty($requerimiento_id) ){
    
    $btn_print_html = Html::a('<span class="glyphicon glyphicon-print"></span>',
        Url::to(['sprint-requerimientos/print-historia-usuario', 'sprint_id' => $sprint_id, 'requerimiento_id' => $requerimiento_id]), 
        [
            'title' => 'Imprimir HU',
            'target'=>'_blank',
            'data-toggle'=>'tooltip', 
            'style' => 'color:white'
        ]
    );   
}
?>

<div class="bs-example">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#sectionA">DETALLE HISTORIA DE USUARIO</a></li>
        <li><a data-toggle="tab" href="#sectionB">IMPLEMENTACION</a></li>
        <li><a data-toggle="tab" href="#sectionC">PRUEBAS</a></li>
    </ul>
    <div class="tab-content">
        <div id="sectionA" class="tab-pane fade in active">
            <br>
            <!-- NUEVOS CAMBIOS 09/01/2018 -->
            <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
           
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-custom">
                    <h3 class="panel-title">
                        <div class="row">
                            <div class="col-lg-11">
                                <b>HISTORIA DE USUARIO:</b>
                            </div>
                            <div class="col-lg-1" style="text-align: right;">
                                <?= $btn_print_html ?>
                            </div>
                        </div>
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
                        <div class="col-lg-4">
                            <?= $form->field($model, 'requerimiento_descripcion')->textarea(['rows' => '3'])->label('* Como(Rol):') ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'requerimiento_funcionalidad')->textarea(['rows' => '3'])->label('* Necesito(Funcionalidad):') ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'requerimiento_justificacion')->textarea(['rows' => '3'])->label('* Para(Finalidad):') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'observaciones')->textarea(['rows' => '3']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <?= $form->field($model, 'divulgacion')->dropDownList([ 0 => 'No requiere', 1 => 'Informativo',  2 => 'Capacitacion formal'], ['prompt' => 'Seleccione plan' ])->label('Plan de divulgación:') ?>
                        </div>
                        
                        <div class="col-lg-9">
                            <?php
                                if (!$model->isNewRecord){
                                    
                                    $carpeta = Yii::getAlias('@web/uploads');

                                    $initial_preview = array();
                                    $initial_preview_config = array();

                                    foreach ($archivos as $archivo) {

                                        $initial_preview[] = $carpeta.'/'.$archivo["archivo_alias"];

                                        if ($archivo["archivo_tipo"] == 'png' || $archivo["archivo_tipo"] == 'jpg'){

                                            $initial_preview_config[] = ['filename' => $archivo["archivo_nombre"].".".$archivo["archivo_tipo"], 'caption' => $archivo["archivo_nombre"].".".$archivo["archivo_tipo"], 'size' => $archivo["archivo_peso"], 'url' => Url::to(['requerimientos/delete-archivo', 'archivo_id' => $archivo["archivo_id"]]) , 'key' => '3'];

                                        }else{

                                            $initial_preview_config[] = ['filename' => $archivo["archivo_nombre"].".".$archivo["archivo_tipo"], 'caption' => $archivo["archivo_nombre"].".".$archivo["archivo_tipo"], 'size' => $archivo["archivo_peso"], 'type' => $archivo["archivo_tipo"], 'url' => Url::to(['requerimientos/delete-archivo', 'archivo_id' => $archivo["archivo_id"]]) , 'key' => '3'];

                                        }

                                    }

                                    //echo '<pre>';print_r($initial_preview_config); exit;

                                    echo $form->field($model, 'archivos[]')->widget(FileInput::classname(), [
                                        'options' => ['multiple' => true],
                                        'pluginOptions' => [
                                            'initialPreview'=> $initial_preview,
                                            'initialPreviewAsData'=>true,
                                            'initialPreviewFileType' => 'image',
                                            'initialPreviewConfig' => $initial_preview_config,
                                            'overwriteInitial'=>false,
                                            'showPreview' => true,
                                            'showCaption' => true,
                                            'showRemove' => true,
                                            'showUpload' => false,
                                            'purifyHtml'=> true,
                                            'preferIconicPreview' => true,
                                            'previewFileIconSettings' => [
                                                'pdf' => '<i class="fa fa-file-pdf-o text-danger"></i>',
                                                'png' => '<i class="fa fa-file-photo-o text-primary"></i>',
                                                'jpg' => '<i class="fa fa-file-photo-o text-danger"></i>'
                                            ],
                                            'previewFileType' => 'any',
                                            'maxFileCount' => 5,
                                            'maxFileSize'=> 10240,
                                            'allowedFileExtensions'=>['jpg','png','pdf'],

                                        ]
                                    ])->label('Archivos:');

                                }
                            ?>
                        </div>
                        
                    </div>
                </div>
                <div class="panel-footer">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear Requerimiento' : 'Actualizar HU', ['id'=>'boton1' , 'onclick' =>'validarBoton()', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <?php

                $toolbar_tareas = array("content" => NULL); 
                $action_template_tareas = "";

                if(!empty($sprint_id)){

                    $sprint_estado = app\models\Sprints::getSprintEstado($sprint_id)->estado;
                    
                    if ($sprint_estado == '0'){

                        $toolbar_tareas = array(
                            "content" => Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Tarea', '#', [
                                            'class' => 'btn btn-success botones',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modal',
                                            'data-url' => Url::to(['create-requerimientos-tareas', 'sprint_id' => $sprint_id ,'requerimiento_id' => $model->requerimiento_id]),
                                            'data-pjax' => '0',
                                            'data-opcion' => 'modal1-create'
                                        ]),
                        );

                        $action_template_tareas = "{update}{delete}"; 

                    }else if ($sprint_estado == '0' || $sprint_estado == '1' && $model->sw_soporte == '1'){


                        $toolbar_tareas = array(
                            "content" => Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Tarea', '#', [
                                            'class' => 'btn btn-success botones',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modal',
                                            'data-url' => Url::to(['create-requerimientos-tareas', 'sprint_id' => $sprint_id ,'requerimiento_id' => $model->requerimiento_id]),
                                            'data-pjax' => '0',
                                            'data-opcion' => 'modal1-create'
                                        ]),
                        );

                        $action_template_tareas = "{update}{delete}"; 

                    }
                }
                
                //var_dump($sprint_estado);exit;

                if( !$model->isNewRecord ){
				
            ?>  
            <div class="row">
                <div class="col-lg-12">
                    <?php Pjax::begin(['id' => 'grid_tareas', 'timeout' => FALSE]) ?>
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
                            'content' => $toolbar_tareas
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
                                'template' => $action_template_tareas,
                                'buttons' => [
                                    'update' => 
                                    function ($url, $model, $key) use ($sprint_id) {
										
										// echo "<pre>"; print_r($sprint_id); exit;
										
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                                    'class' => 'botones',
                                                    'title' => Yii::t('yii', 'Actualizar'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#modal',
                                                    'data-url' => Url::to(['update-requerimientos-tareas', 'tarea_id' => $model->tarea_id, 'sprint_id' => $sprint_id]),
                                                    'data-pjax' => '0',
                                                    'data-opcion' => 'modal1-update'
                                        ]);
                                    },           
                                    'delete' => function ($url, $model, $key)  use ($sprint_id) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'aria-label' => Yii::t('yii', 'Delete'),
                                            'onclick' => "
                                                if (confirm('Esta seguro de eliminar este registro?')) {
                                                     $.ajax('".Url::to(['delete-requerimientos-tareas', 'tarea_id' => $model->tarea_id, 'sprint_id' => $sprint_id])."', {
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
                    <?php Pjax::begin(['id' => 'grid_procesos_involucrados', 'timeout' => FALSE ]) ?>
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

                    <?php Pjax::begin(['id' => 'grid_perfiles_usuarios' , 'timeout' => FALSE]) ?>

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
                }
            ?>
        </div>
        <div id="sectionB" class="tab-pane fade">
            <br>
                <?php 
                
                $lista_usuarios = Usuarios::getListaUsuarios();
 
                if (!$model->isNewRecord){
                    
                    if ($RI_model->isNewRecord){
                        $RI_model->soporte_entregado_fecha = date("Y-m-d");
                        $RI_model->produccion_entregado_fecha = date("Y-m-d");
                        $RI_model->soporte_entregado_por = Yii::$app->user->identity->usuario_id;
                        $RI_model->produccion_entregado_por = Yii::$app->user->identity->usuario_id;
                    }
                
                Pjax::begin(['id' => 'form-requerimientos_implementacion', 'enablePushState'=>false, 'enableReplaceState' => false]);
                $form = ActiveForm::begin([
                    'action' => $RI_model->isNewRecord ? Url::to(['requerimientos-implementacion/create', 'sprint_id' => $sprint_id , 'requerimiento_id' => $model->requerimiento_id]) : Url::to(['requerimientos-implementacion/update', 'sprint_id' => $sprint_id, 'requerimiento_id' => $model->requerimiento_id]),
                    //'id' => 'forum_post', 
                    'method' => 'post',
                    'options' => ['data-pjax' => true ],
                ]);
                ?> 
                <?= $form->field($RI_model, 'requerimiento_id')->hiddenInput(['value'=> $model->requerimiento_id ])->label(false); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-body">
                                <table class="table table-bordered table-hover">
                                  <thead>
                                    <tr>
                                        <th style="text-align: center;" colspan="3">CAPACITACION A SOPORTE</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                        <td>
                                            <?= $form->field($RI_model, 'soporte_entregado_por')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Entregado por:");
                                            ?> 
                                        </td>
                                        <td>
                                            <?= $form->field($RI_model, 'soporte_entregado_fecha')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?>                                
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>
                                            <?= $form->field($RI_model, 'soporte1_recibio_capacitacion')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Recibio capacitacion #1:");
                                            ?> 
                                      </td>
                                      <td> 
                                            <?= $form->field($RI_model, 'soporte1_fecha_entrega')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?>   
                                      </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $form->field($RI_model, 'soporte2_recibio_capacitacion')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Recibio capacitacion #2:");
                                            ?> 
                                        </td>
                                        <td>
                                            
                                            <?= $form->field($RI_model, 'soporte2_fecha_entrega')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?>   
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $form->field($RI_model, 'usuario_recibe')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Usuario recibe requerimiento:");
                                            ?>        
                                        </td>
                                    </tr>
                                  </tbody>
                                </table>  
                            </div>
                            <div class="box-footer clearfix">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-body">
                                <table class="table table-bordered table-hover">
                                  <thead>
                                    <tr>
                                        <th style="text-align: center;" colspan="3">ACTUALIZACION EN PRODUCCION</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                            <?= $form->field($RI_model, 'produccion_entregado_por')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Entregado por:");
                                            ?> 
                                      </td>
                                      <td>
                                            <?= $form->field($RI_model, 'produccion_entregado_fecha')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?> 
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                            <?= $form->field($RI_model, 'produccion1_recibio_capacitacion')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Recibio capacitacion #1:");
                                            ?>   
                                      </td>
                                      <td>
                                            <?= $form->field($RI_model, 'produccion1_fecha_entrega')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                            <?= $form->field($RI_model, 'produccion2_recibio_capacitacion')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Recibio capacitacion #2:");
                                            ?>         
                                      <td>
                                            <?= $form->field($RI_model, 'produccion2_fecha_entrega')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $form->field($RI_model, 'usuario_aprueba_produccion')->widget(Select2::className(),[
                                                'data' => $lista_usuarios,
                                                'theme' => Select2::THEME_DEFAULT,
                                                'language'=>'es',
                                                'options' => ['placeholder'=>''],
                                                'pluginOptions'=>[
                                                    'allowClear'=>true
                                                ],
                                                ])->label("Usuario aprueba actualizacion en produccion:");
                                            ?>  
                                        </td>
                                        <td>     
                                            <?= $form->field($RI_model, 'fecha_subida_produccion')->widget(DatePicker::classname(), [
                                                //'name' => 'dp_3',
                                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                                'size' => 'xs',
                                                'pluginOptions' => [
                                                    'autoclose'=>true,
                                                    'format' => 'yyyy-mm-dd',
                                                    'startDate' => '2018-01-01'
                                                ]
                                                ])->label('Fecha de entrega:'); 
                                            ?>      
                                        </td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                            <div class="box-footer clearfix"></div>
                        </div>
                    </div>           
                </div>
                <div class="form-group"> 
                    <?= Html::submitButton($RI_model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $RI_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
                </div> 
                <?php
                    ActiveForm::end();
                    Pjax::end();
                }
                ?>
            </div>
            <div id="sectionC" class="tab-pane fade">
                <br>

                <?php
                if (!$model->isNewRecord){
                ?>

                <?php Pjax::begin(['id' => 'grid-requerimientos_pruebas' , 'timeout' => FALSE]) ?>

                    <?= GridView::widget([
                        'dataProvider' => $RP_dataProvider,
                        'filterModel' => $RP_searchModel,
                        'panel' => [
                            'heading' => '<b>PRUEBAS FUNCIONALES:</b>',
                            'headingOptions' => ['class'=>'panel-heading panel-heading-custom'],
                            'type' => GridView::TYPE_DEFAULT,
                        ],
                        'toolbar' => [
                            'content' => 
                                Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Prueba', '#', [
                                'class' => 'btn btn-success botones',
                                'data-toggle' => 'modal',
                                'data-target' => '#modal',
                                'data-url' => Url::to(['requerimientos-pruebas/create', 'sprint_id' => $sprint_id, 'requerimiento_id' => $model->requerimiento_id]),
                                'data-pjax' => '0',
                                'data-opcion' => 'modal4-create', 
                            ]),
                        ],
                        'columns' => [
                            [
                                'class'=>'kartik\grid\SerialColumn',
                                'width'=>'1%',
                                'header'=>'#',
                                //'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                            ],
                            [
                                'attribute' => 'prueba_id',
                                'label' => 'ID',
                                'value' => 'prueba_id',
                                'filter' => FALSE,
                                //'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                                'contentOptions' => ['style' => 'width:1%;'],
                            ],
                            [
                                'attribute' => 'fecha_entrega',
                                'label' => 'FECHA DE ENTREGA',
                                'filter' => FALSE,
                                //'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                                'contentOptions' => ['style' => 'width:10%;'],
                            ],
                            [
                                'attribute' => 'fecha_prueba',
                                'label' => 'FECHA DE PRUEBA',
                                'filter' => FALSE,
                                //'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                                'contentOptions' => ['style' => 'width:10%;'],
                            ],
                            [
                                'attribute' => 'usuario_pruebas',
                                'label' => 'ING. PRUEBAS',
                                'value' => 'usuarioPruebas.nombreCompleto',
                                'filter' => FALSE,
                                //'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                                'contentOptions' => ['style' => 'width:20%;'],
                            ],
                            [
                                'attribute' => 'observaciones',
                                'label' => 'OBSERVACIONES',
                                'filter' => FALSE,
                                //'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                                'contentOptions' => ['style' => 'width:40%;'],
                            ],
                            [
                                'attribute' => 'estado',
                                'label' => 'ESTADO',
                                'filter' => FALSE,
                                'contentOptions' => ['style' => 'width:10%;'],
                                'value' => function ($data) {

                                    if($data['estado'] == 0){
                                        return 'No Aprobado';
                                    }
                                    if($data['estado'] == 1){
                                        return 'Aprobado';
                                    }
                                    return 'Error';
                                }, 
                            ],
                            [
                                'class'=>'kartik\grid\ActionColumn',
                                'header' => 'ACCIONES',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                                    'class' => 'botones',
                                                    'title' => Yii::t('yii', 'Actualizar'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#modal',
                                                    'data-url' => Url::to(['requerimientos-pruebas/update', 'prueba_id' => $model->prueba_id]),
                                                    'data-pjax' => '0',
                                                    'data-opcion' => 'modal4-update'
                                        ]);
                                    },                            

                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'aria-label' => Yii::t('yii', 'Delete'),
                                            'onclick' => "
                                                if (confirm('Esta seguro de eliminar este registro?')) {
                                                    $.ajax('".Url::to(['requerimientos-pruebas/delete', 'prueba_id' => $model->prueba_id, 'requerimiento_id' => $model->requerimiento_id])."', {
                                                        type: 'POST'
                                                    }).done(function(data) {
                                                        $.pjax.reload({container: '#grid-requerimientos_pruebas'});
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
                <?php
                    }
                ?>
                
            </div>
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
            var width_modal = '30%';


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
            else if(propiedades_modal[0] === 'modal4'){
                
                width_modal = '80%';
               
                if (propiedades_modal[1] === 'create'){

                    texto_titulo = 'CREAR PRUEBA FUNCIONAL';
                    $('#modal').find('.modal-header').css('background-color','#008C4D');

                }else if (propiedades_modal[1] === 'update'){

                    $('#modal').find('.modal-header').css('background-color','#367EA8');
                    texto_titulo = 'MODIFICAR PRUEBA FUNCIONAL';

                }
            }
            
            $('#modal').children().css('width', width_modal);
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
    Modal::begin([
        'id' => 'modal',
        'header' => '<h5 style="font-weight: bold; color:white;" id="titulo_perfiles_usuario" class="modal-title"></h5>',
    ]);
    echo "<div class='well'></div>";
    Modal::end();
?>
