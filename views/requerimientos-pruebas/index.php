<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;


$this->title = 'PRUEBAS';

?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #f56954;
        border-color: #ddd;
    }
</style>
    <div class="row">
        <div class="col-lg-12">
            <?= 
                GridView::widget([
                'id' => 'requerimientos-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="fa fa-check-square-o"></i> Historias de usuario </h3>',
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'bordered' => true,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'H.U ID',
                        'attribute' => 'requerimiento_id',
                        'contentOptions' => ['style' => 'width:10px;'],
                    ],
                    [
                        'attribute' => 'sprint_id',
                        'label' => 'SPRINT',
                        'value' => 'sprint.sprint_alias',
                        'contentOptions' => ['style' => 'width:10px;'],
                        'filter' => Html::activeDropDownList($searchModel, 'sprint_id', \app\models\Sprints::getListaSprints(),['class'=>'form-control','prompt' => 'Seleccione Sprint']),
                        //'filter' => FALSE,
                    ],
                    [
                        'attribute' => 'requerimiento_titulo',
                        'label' => 'HISTORIA USUARIO',
                        'value' => 'requerimiento.requerimiento_titulo',
                        'contentOptions' => ['style' => 'width:150px;'],
                        //'filter' => FALSE,
                    ],       
                    [
                        'label' => 'TIEMPO DESARROLLO',
                        'attribute' => 'tiempo_desarrollo',
                        'contentOptions' => ['style' => 'width:10px;'],
                    ],
                    [
                        'label' => 'USUARIO ASIGNADO',
                        'attribute' => 'nombre_usuario_asignado',
                        'value' => 'usuarioAsignado.nombreCompleto',
                        'filter' => Html::activeDropDownList($searchModel, 'usuario_asignado', \app\models\Usuarios ::getListaDevelopers(),['class'=>'form-control','prompt' => 'Seleccione Desarrollador']),
                        'contentOptions' => ['style' => 'width:10px;'],
                    ],
                    [
                        'label' => 'FECHA REQUERIMIENTO',
                        'attribute' => 'fecha_requerimiento',
                        'value' => 'requerimiento.fecha_requerimiento',
                        'filterType'=> GridView::FILTER_DATE, 
                        'filterWidgetOptions' => [
                            'options' => ['placeholder' => 'Seleccione Fecha'],
                            'type' => 3,
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'autoclose'=>true,
                                ]
                            ],
                        'contentOptions' => ['style' => 'width:200px;'],
                    ],
                    [
                        'label' => 'ESTADO',
                        'attribute' => 'estado',
                        'value' => function ($data) {
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
                        'contentOptions' => ['style' => 'width:10px;'],
                        'template' => '{update} {print_HU}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-search"></span>', Url::to(['requerimientos/update', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]), [
                                    'title' => 'Detalle H.U',
                                ]);
                            },
                            'print_HU' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-print"></span>', Url::to(['sprint-requerimientos/print-historia-usuario', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]), [
                                    'title' => 'Imprimir HU',
                                    'target'=>'_blank',
                                    'data-toggle'=>'tooltip', 
                                ]);
                            },       
                        ]
                    ],
                ],              
                'toolbar' => FALSE,
            ]); 
            ?>
        </div>
    </div>