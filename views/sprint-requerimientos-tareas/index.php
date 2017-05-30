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

            <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Tareas</h3>',
                'type' => GridView::TYPE_DEFAULT,
                'footer' => Html::a('Crear Tarea', Url::to(['sprint-requerimientos-tareas/create']),['class' => 'btn btn-success']),
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
                // 'tiempo_desarrollo',

                ['class' => 'yii\grid\ActionColumn'],
            ],
                'toolbar' => FALSE,
            ]); ?>
            
            

        </div>
        <div class="col-lg-6">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulario Para Crear O Actualizar Tareas</h3>
                </div>
                <div class="box-body">
                    <br><br><br><br><br><br><br><br><br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</div>
