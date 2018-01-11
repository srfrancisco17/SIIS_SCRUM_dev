<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\Pjax;


$this->title = 'Requerimientos';
$this->params['breadcrumbs'][] = $this->title;
?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #f56954;
        border-color: #ddd;
    }
</style>
<div class="requerimientos-index">

    <div class="row">
        <div class="col-lg-12">
     
            <?php Pjax::begin(); ?>
            <?= 
                GridView::widget([
                'id' => 'requerimientos-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="fa fa-check-square-o"></i> Tabla Requerimientos </h3>',
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'bordered' => true,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'ID',
                        'attribute' => 'requerimiento_id',
                        'contentOptions' => ['style' => 'width:80px;'],
                    ],
                    [
                        'attribute' => 'comite_id',
                        'label' => 'Comite',
                        'value' => 'comite.comite_alias',
                        'filter' => FALSE,
                    ],
                    //'comite_id',
                    'requerimiento_titulo',
                    /*
                    [
                        'attribute' => 'usuario_asignado',
                        'value' => 'sprintRequerimientos2.usuarioAsignado.nombreCompleto',
                        'contentOptions' => ['style' => ' width:150px;'],
                    ], 
                    */
                    [
                        'label' => 'Tiempo Desarrollo',
                        'attribute' => 'tiempo_desarrollo',
                        'contentOptions' => ['style' => 'width:10px;'],
                    ],
                    //'requerimiento_descripcion:ntext',
                    //'requerimiento_justificacion:ntext',
                    // 'usuario_solicita',
                    // 'departamento_solicita',
                    // 'observaciones:ntext',
                    [
                        'attribute' => 'fecha_requerimiento',
                        'filterType'=> GridView::FILTER_DATE, 
                        'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Seleccione Fecha'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose'=>true,
                            ]
                        ],
                        'contentOptions' => ['style' => 'width:200px;'],
                    ],
                    [
                        'label' => 'Estado',
                        'attribute' => 'estado',
                        'value' => function ($data) {
                            //print_r($data);  
                            if($data['estado'] == 0){
                                return 'Inactivo';
                            }
                            if($data['estado'] == 1){
                                return 'Activo';
                            }
                            if($data['estado'] == 2){
                                return 'En Espera';
                            }
                            if($data['estado'] == 3){
                                return 'En Progreso';
                            }
                            if($data['estado'] == 4){
                                return 'Terminado';
                            }
                            if($data['estado'] == 5){
                                return 'No Cumplida';
                            }
                            return 'Error';
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo', '2' => 'En Espera', '3' => 'En Progreso', '4' => 'Terminado'],['class'=>'form-control','prompt' => '']),
                        'contentOptions' => ['style' => 'width:100px;'],
                    ],
                    [
                        'class'=>'kartik\grid\ActionColumn',
                        'template' => Yii::$app->user->identity->tipo_usuario == 1 ? '{view}{update}{delete}{tareas}' : '{tareas}',
                        'buttons' => [
//                            'tareas' => function ($url, $model, $key) {
//                                return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['requerimientos-tareas/index', 'requerimiento_id' => $model->requerimiento_id]), [
//                                            'id' => 'activity-index-link2',
//                                            'title' => Yii::t('yii', 'Tareas'),
//                                ]);
//                            },
                            'tareas' => function ($url, $model, $key) {
                                
                                $count = 0;
                            
                                foreach ($model->requerimientosTareas as $valor) {
                                    
                                    if ($valor->ultimo_estado == 5){
                                       $count++;
                                    }
                                }
                                
                                if ($count > 0){
                                    return '---';
                                }else{
                                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['requerimientos-tareas/index', 'requerimiento_id' => $model->requerimiento_id]), []); 
                                }
                                
                            },
                        ]
                    ],
                ],              
                'toolbar' =>  (Yii::$app->user->identity->tipo_usuario == 1) ?  
                [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Requerimientos', ['create'], ['class' => 'btn btn-success'])
                    ],
                ]                                             
                : FALSE,
            ]); 
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
