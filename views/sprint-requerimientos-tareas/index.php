<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\models\SprintRequerimientosTareas;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintRequerimientosTareasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprint Requerimientos Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-tareas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <!--
    <p>
        <?= Html::a('Create Sprint Requerimientos Tareas', ['create'], ['class' => 'btn btn-success']) ?>
    
        
    </p>
    -->
    <div class="row">
        <div class="col-lg-12">
            
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Requerimiento</h3>
                </div>
                <?php
                //echo $requerimiento->departamentoSolicita->descripcion;
                ?>
                <div class="box-body">         
                    <?= DetailView::widget([
                    'model' => $requerimiento,
                    'attributes' => [
                        'requerimiento_id',
                        'comite_id',
                        'requerimiento_titulo',
                        'requerimiento_descripcion:html',
                        'requerimiento_justificacion:html',
                        //'usuario_solicita',
                        [                      
                        'label' => 'Usuario Que Solicita',
                        'value' => $requerimiento->usuarioSolicita->nombres.' '.$requerimiento->usuarioSolicita->apellidos,
                        ],
                        [                      
                        'label' => 'Departamento Que Solicita', 
                        'value' => empty($requerimiento->departamentoSolicita->descripcion) ? '' : $requerimiento->departamentoSolicita->descripcion,
                        ],
                        'observaciones:html',
                        'fecha_requerimiento',
                        //'estado',
                        [                      
                        'label' => 'Estado',
                        'value' => $requerimiento->estado0->descripcion,
                        ],
                    ],
                ]) ?>     
                </div>
                <div class="box-footer">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php Pjax::begin(); ?>
                <?= GridView::widget([
                'id' => 'tareas-grid',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Tareas</h3>',
                    'type' => GridView::TYPE_DEFAULT,
                    'footer' => Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Tarea', '#', [
                            'id' => 'activity-index-link',
                            'class' => 'btn btn-success',
                            'data-toggle' => 'contenido',
                            'data-target' => '#contenido',
                            'data-url' => Url::to(['create','sprint_id' => $sprint_id, 'requerimiento_id' => $requerimiento_id]),
                            'data-pjax' => '0',
                        ]),
                    'after' => FALSE,
                    'before' => FALSE,
                ],
                'panelFooterTemplate' => '{footer}{pager}{toolbar}',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'tarea_id',
                    //'sprint_id',
                    //'requerimiento_id',
                    'tarea_titulo',
                    'tarea_descripcion:ntext',
                    // 'estado',
                    'tiempo_desarrollo',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}{delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return ($model->sprint->estado != 4 && $model->sprint->estado != 0) ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                            'id' => 'activity-index-link',
                                            'title' => Yii::t('yii', 'Update'),
                                            'data-toggle' => 'contenido',
                                            'data-target' => '#contenido',
                                            'data-url' => Url::to(['update', 'id' => $model->tarea_id]),
                                            'data-pjax' => '0',
                                ]) : '';
                            },
                            'delete' => function($url, $model){
                                return ($model->sprint->estado != 4 && $model->sprint->estado != 0) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->tarea_id, 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id], [
                                    'class' => '',
                                    'title'=>'Eliminar',
                                    'data' => [
                                        'confirm' => '¿Está seguro de eliminar este elemento?',
                                        'method' => 'post',
                                    ],
                                ]) : '';
                            }
                        ],
                    ],
                ],
                'toolbar' => FALSE,
                ]); ?>
            <?php Pjax::end(); ?>
            <?php
            $this->registerJs(
                    "$(document).on('click', '#activity-index-link', (function() {
                            $.get(
                                $(this).data('url'),
                                function (data) {
                                    $('#contenido').html(data);
                                    $('#sprintrequerimientostareas-tarea_titulo').focus();
                                    
                                }
                            );
                    }));"
            );
            ?>
            
        </div>
        <div class="col-lg-6">
            <div id="contenido"></div>
        </div>
    </div>
</div>

