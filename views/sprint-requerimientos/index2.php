<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintRequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprint Requerimientos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-1">
        <div class="sprint-requerimientos-index">
            <?= GridView::widget([
                'id' => 'sprintRequerimiento-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-expand"></i> SPRINT Numero)</h3>',
                    'type' => GridView::TYPE_DEFAULT,
                    'footer' => '',
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'sprint_id',
                    [
                        'attribute' => 'requerimiento_id',
                        'label' => 'Requerimiento',
                        'value' => 'requerimiento.requerimiento_titulo',
                        'filter' => FALSE,
                    ],
                    [
                        'attribute' => 'requerimiento_id',
                        'label' => 'Requerimiento',
                        'value' => 'requerimiento.requerimiento_titulo',
                        'filter' => FALSE,
                    ],
                    [
                        'label' => 'Usuario Asignado',
                        'attribute' => 'usuario_asignado',
                        'value' => 'usuarioAsignado.nombres',
                        'filter' => FALSE,
                    ],
                    [
                        'attribute' => 'tiempo_desarrollo',
                        'filter' => FALSE,
                    ],
                    //'usuario_asignado',
                    //'tiempo_desarrollo',
                    [
                        'attribute' => 'estado',
                        'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo'],['class'=>'form-control','prompt' => '']),
                        'value' => function ($data) {
                                //print_r($data);
                                //Condicionales que me permiten hacer una equivalencia de valores numericos en textos
                                if($data['estado'] == 0){
                                    return 'Inactivo';
                                }
                                if($data['estado'] == 1){
                                    return 'Activo';
                                }
                                return 'Null';
                        },
                    ],
                    //['class'=>'yii\grid\ActionColumn',],
                ],
                'pjax' => true,                                                 
            ]); ?>            
        </div>
    </div>
</div>