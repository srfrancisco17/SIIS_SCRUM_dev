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
    <div class="col-md-10 col-md-offset-1">
        <?= GridView::widget([
        'id' => 'sprintRequerimiento-grid',
        'dataProvider' => $dataProvider2,
        //'filterModel' => $searchModel2,
        'summary'=>'',
    
        //'tableOptions' => ['style' => 'background-color:black; border:1px solid black; '],
        'options' => ['style' => 'border:1px dotted black; '],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'requerimiento_id',
            [
                'class'=>'kartik\grid\SerialColumn',
                'contentOptions'=>['class'=>'kartik-sheet-style'],
                'width'=>'36px',
                'header'=>'#',
                'headerOptions'=>['style' => 'background-color:#292B2C; color:white;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'Requerimiento',
                'value' => 'requerimiento.requerimiento_titulo',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#292B2C;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
                
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'Descripcion Requerimiento',
                'value' => 'requerimiento.requerimiento_descripcion',
                'filter' => FALSE,
                'format' => 'html',
                'headerOptions'=>['style' => 'background-color:#292B2C;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'Usuario Que Solicita',
                //'value' => 'requerimiento.usuarioSolicita.nombres',
                'value' => function($model) { return $model->requerimiento->usuarioSolicita->nombres.' '.$model->requerimiento->usuarioSolicita->apellidos;},
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#292B2C;'],
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
                'attribute' => 'tiempo_desarrollo',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#292B2C;'],
                //'contentOptions' => ['style' => 'background-color:red;  width:10px;'],
                
            ],
            //'usuario_asignado',
            //'tiempo_desarrollo',
            [
                'class'=>'yii\grid\ActionColumn',
                'headerOptions'=>['style' => 'background-color:#292B2C;'],
                //'contentOptions' => ['style' => 'background-color:red; '],
                'template' => '{requerimientos}',
                'buttons' => [
                    'requerimientos' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['sprint-requerimientos-tareas/index','sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]), [
                                'id' => 'activity-index-link2',
                                'class' => 'btn btn-success',
                         
                                'title' => Yii::t('yii', 'Asignar Tareas'),
                    ]);
                },
                ]
            ],
        ],
        'pjax' => true,                                                 
    ]);?>     
        
    </div>
</div>
           
