<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
?>
<br>
<div class="row">
    <div class="col-md-12">
        <?= GridView::widget([
        'id' => 'sprintRequerimiento-grid',
        'dataProvider' => $dataProvider2,
        //'filterModel' => $searchModel2,
        'summary'=>'',
        'options' => ['style' => 'border:5px groove; '],
        'columns' => [
            [
                'class'=>'kartik\grid\SerialColumn',
                'width'=>'1%',
                'header'=>'#',
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'H.U ID',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:2%;'],
                
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'H.U TITULO',
                'value' => 'requerimiento.requerimiento_titulo',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:20%;'],
                
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'H.U COMO',
                'value' => function ($data) {
                    return html_entity_decode(strip_tags($data->requerimiento->requerimiento_descripcion));
                },
                'filter' => FALSE,
                'format' => 'text',
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:20%;'],
            ],
            [
                'attribute' => 'requerimiento_id',
                'label' => 'H.U NECESITO',
                'value' => function ($data) {
                    return html_entity_decode(strip_tags($data->requerimiento->requerimiento_funcionalidad));
                },
                'filter' => FALSE,
                'format' => 'text',
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:20%;'],
            ],           
            [
                'attribute' => 'requerimiento_id',
                'label' => 'H.U PARA',
                'value' => function ($data) {
                    return html_entity_decode(strip_tags($data->requerimiento->requerimiento_justificacion));
                },
                'filter' => FALSE,
                'format' => 'text',
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:20%;'],
            ],             
            [
                'attribute' => 'requerimiento_id',
                'label' => 'USUARIO SOLICITANTE',
                'value' => function($model) { return $model->requerimiento->usuarioSolicita->nombres.' '.$model->requerimiento->usuarioSolicita->apellidos;},
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:10%;'],
            ],
            [
                'attribute' => 'tiempo_desarrollo',
                'label' => 'H.U TIEMPO',
                'filter' => FALSE,
                'headerOptions'=>['style' => 'background-color:#3cbcab; color:#245269;'],
                'contentOptions' => ['style' => 'width:1%;'],
            ],
            [
                'class'=>'yii\grid\ActionColumn',
                'headerOptions'=>['style' => 'background-color:#3cbcab;'],
                'contentOptions' => ['style' => 'width:5%;text-align:center;'],
                'template' => '{requerimientos} {print_HU}',
                'buttons' => [
                    'requerimientos' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['requerimientos/update', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]), [
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
    ]);?>     
    </div>
</div>
           
