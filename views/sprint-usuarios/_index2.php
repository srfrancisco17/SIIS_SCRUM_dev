<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel2 app\models\SprintRequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCss(
        ".table-bordered > thead > tr > th,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > tbody > tr > td,
.table-bordered > tfoot > tr > td {
    border: 1px solid black;
}
body{
background-color: red;
}
"
        );

$this->title = 'Sprint Requerimientos';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= GridView::widget([
        'id' => 'sprintRequerimiento-grid',
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'summary'=>'', 
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
                'attribute' => 'requerimiento_id:html',
                'label' => 'Descripcion Requerimiento',
                'value' => 'requerimiento.requerimiento_descripcion',
                'filter' => FALSE,
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
            ],
            //'usuario_asignado',
            //'tiempo_desarrollo',
            [
                'attribute' => 'estado',
                'filter' => Html::activeDropDownList($searchModel2, 'estado', ['0'=>'Inactivo', '1'=>'Activo'],['class'=>'form-control','prompt' => '']),
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
            [
                'class'=>'yii\grid\ActionColumn',
                'template' => '{requerimientos}',
                'buttons' => [
                    'requerimientos' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['sprint-requerimientos/index2','sprint_id' => $model->sprint_id]), [
                                'id' => 'activity-index-link2',
                                'title' => Yii::t('yii', 'Requerimientos'),
                    ]);
                },
                ]
            ],
        ],
        'pjax' => true,                                                 
    ]); 
    ?>            
