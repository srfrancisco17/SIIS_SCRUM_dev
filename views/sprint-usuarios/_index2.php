<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel2 app\models\SprintRequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<br>
<div class="row">
    <div class="col-md-12">
        <?= GridView::widget([
        'id' => 'sprintRequerimiento-grid',
        'dataProvider' => $dataProvider2,
        //'filterModel' => $searchModel2,
        'summary'=>'',
        //'showPageSummary' => true,
        
        //'tableOptions' => ['style' => 'background-color:black; border:1px solid black; '],
        'options' => ['style' => 'border:5px groove; '],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'requerimiento_id',
            [
                'class'=>'kartik\grid\SerialColumn',
                'width'=>'1%',
                'header'=>'#',
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                //'contentOptions' => ['style' => 'width: 1%;'],

            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'id',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
                'contentOptions' => ['style' => 'width:2%;'],
                
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'Requerimiento',
                'value' => 'requerimiento.requerimiento_titulo',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
                'contentOptions' => ['style' => 'width:20%;'],
                
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'Descripcion Requerimiento',
                //'value' => 'requerimiento.requerimiento_descripcion',
                'value' => function ($data) {
                    return html_entity_decode(strip_tags($data->requerimiento->requerimiento_descripcion));
                },
                'filter' => FALSE,
                'format' => 'text',
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
                'contentOptions' => ['style' => 'width:40%;'],
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'Usuario Que Solicita',
                //'value' => 'requerimiento.usuarioSolicita.nombres',
                'value' => function($model) { return $model->requerimiento->usuarioSolicita->nombres.' '.$model->requerimiento->usuarioSolicita->apellidos;},
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:10%;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
            ],
            /*
            [
                'label' => 'Usuario Asignado',
                'attribute' => 'usuario_asignado',
                'value' => 'usuarioAsignado.nombres',
                'filter' => FALSE,
            ],
            */
            [
                'attribute' => 'requerimiento.tiempo_desarrollo',
                'value' => 'requerimiento.tiempo_desarrollo',
                'label' => 'Hr',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:1%;'],
            ],


            //'usuario_asignado',
            //'tiempo_desarrollo',
            [
                'class'=>'yii\grid\ActionColumn',
                'headerOptions'=>['style' => 'background-color:#3cbcab;'],
                'contentOptions' => ['style' => 'width:5%;'],
                'template' => '{requerimientos}',
                'buttons' => [
                    'requerimientos' => function ($url, $model, $key) {
                    
                    if ($model->sprint->estado == 0 || $model->requerimiento->sw_soporte == 1){
                        
//                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['requerimientos-tareas/index','sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]), [
//                            'id' => 'activity-index-link2',
//                            'class' => 'btn btn-success',
//                         
//                            'title' => Yii::t('yii', 'Asignar Tareas'),
//                        ]);
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['requerimientos/update', 'requerimiento_id' => $model->requerimiento_id]), [
                            'id' => 'activity-index-link2',
                            'class' => 'btn btn-success',
                            'title' => Yii::t('yii', 'Detalle Requerimiento'),
                        ]);
                        
                    }else{
                        return '<b style="font-size: 8pt;">No Disponible</b>';
                    }
                    
                },
                ]
            ],
        ],
        'pjax' => true,                                                 
    ]);?>     
        
    </div>
</div>
           
