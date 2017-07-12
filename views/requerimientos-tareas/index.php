<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequerimientosTareasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requerimientos Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-lg-6">
                <?= GridView::widget([
                    'id' => 'grid-requerimientos_tareas',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Tareas</h3>',
                        'type' => GridView::TYPE_DEFAULT,
                        'footer' => FALSE,
                    ],
                    'toolbar' => [
                        'content' => 
                            Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Tarea', '#', [
                            'id' => 'activity-index-link',
                            'class' => 'btn btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'data-url' => Url::to(['create', 'sprint_id' => $sprint_id ,'requerimiento_id' => $modelRequerimiento->requerimiento_id]),
                            'data-pjax' => '0',
                        ]),
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        /*
                        [
                            'label' => 'ID Tarea',
                            'attribute' => 'tarea_id',
                            'contentOptions' => ['style' => 'width:10px;'],
                        ],
                        */
                        //'tarea_id',
                        //'requerimiento_id',
                        'tarea_titulo',
                        'tarea_descripcion:ntext',
                        //'ultimo_estado',
                        [
                            'label' => 'Horas',
                            'attribute' => 'horas_desarrollo',
                            'contentOptions' => ['style' => 'width:10px;'],
                        ],
                        // 'fecha_terminado',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'update' => function ($url, $model, $key) use(&$sprint_id) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                                'id' => 'activity-index-link',
                                                'title' => Yii::t('yii', 'Actualizar'),
                                                'data-toggle' => 'modal',
                                                'data-target' => '#modal',
                                                'data-url' => Url::to(['update', 'sprint_id' => $sprint_id ,'tarea_id' => $model->tarea_id]),
                                                'data-pjax' => '0',
                                    ]);
                                },
                                'delete' => function($url, $model, $key) use(&$sprint_id){
                                    return  Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'sprint_id' => $sprint_id, 'id' => $model->tarea_id], [
                                        'class' => '',
                                        'title'=>'Eliminar',
                                        'data' => [
                                            'confirm' => '¿Está seguro de eliminar este elemento?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                }
                            ],
                        ],
                    ],
                    'pjax' => true,
                ]); ?>
            </div>
    <div class="col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detalles del requerimiento</h3>
            </div>        
            <div class="box-body">

                <?= DetailView::widget([
                    'model' => $modelRequerimiento,
                    'attributes' => [
                        //'requerimiento_id',
                        //'comite_id',

                        'requerimiento_titulo',
                        'requerimiento_descripcion:html',
                        'requerimiento_justificacion:html',
                        //'usuario_solicita',
                        [                      
                            'label' => 'Usuario Que Solicita',
                            'value' => $modelRequerimiento->usuarioSolicita->nombreCompleto,
                        ],
                        [                      
                            'label' => 'Departamento Que Solicita', 
                            'value' => empty($modelRequerimiento->departamentoSolicita->descripcion) ? '' : $modelRequerimiento->departamentoSolicita->descripcion,
                        ],
                        'observaciones:html',
                        'fecha_requerimiento',
                        //'estado',
                        [                      
                            'label' => 'Estado',
                            'value' => $modelRequerimiento->estado0->descripcion,
                        ],
                    ],
                ]) ?>

            </div>
            <div class="box-footer">

            </div>
        </div>            
    </div>
</div>
<?php
$this->registerJs(
        "$(document).on('click', '#activity-index-link', (function() {
                $.get(
                    $(this).data('url'),
                    function (data) {
                        $('.modal-body').html(data);
                        $('#modal').modal();
                    }
                );
            }));"
);

    Modal::begin([
        'id' => 'modal',
        'header' => '<h4 class="modal-title">Tareas</h4>'

    ]);
    echo "<div class='well'></div>";
    Modal::end();
?>



