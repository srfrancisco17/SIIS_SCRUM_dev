<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SprintsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprints';
$this->params['breadcrumbs'][] = $this->title;
?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #ff851b;
        border-color: #ddd;
    }
</style>
<div class="row"> 
    <div class="col-md-12">
        <div class="sprints-index">
            <!--<h1><?= Html::encode($this->title) ?></h1>-->
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'id' => 'sprints-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="fa fa-undo"></i> Tabla Sprints </h3>',
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'sprint_id',
                    'sprint_alias',
                    'fecha_desde',
                    'fecha_hasta',
                    'horas_desarrollo',
                    /*
                    [
                        'attribute' => 'observaciones',
                        'filter'=>FALSE
                    ],
                     */
                    //'estado',
                    [
                        'label' => 'Estado',
                        'attribute' => 'estado',
                        'value' => function ($data) {
                            //print_r($data);  
                            switch ($data['estado']) {
                                case 0:
                                    return "Inactivo";
                                case 1:
                                    return "Activo";
                                case 4:
                                    echo "Terminado";
                                    break;
                                default:
                                   echo "Error";
                            }
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo', '4' => 'Terminado'],['class'=>'form-control','prompt' => '']),
                        'contentOptions' => ['style' => 'width:5px;'],
                    ],
                    [
                        'class'=>'kartik\grid\ActionColumn',
                        'template' => '{update}{delete}{sprints}{master-kanban}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                        'id' => 'activity-index-link',
                                        'title' => Yii::t('yii', 'Actualizar'),
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal',
                                        'data-url' => Url::to(['update', 'id' => $model->sprint_id]),
                                        'data-pjax' => '0',
                            ]);
                            },
                            'sprints' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-check"></span>', Url::to(['sprint-requerimientos/index', 'sprint_id' => $model->sprint_id]), [
                                            'id' => 'activity-index-link2',
                                            'title' => Yii::t('yii', 'Requerimientos'),
                                ]);
                            },
                            'master-kanban' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-table"></span>', Url::to(['sprints/master-kanban', 'sprint_id' => $model->sprint_id]), [
                                            'id' => 'activity-index-link2',
                                            'title' => Yii::t('yii', 'kanban'),
                                ]);
                            },                                    
                        ]
                    ],
                ],
                'toolbar' => [
                        ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Sprint', '#', [
                            'id' => 'activity-index-link',
                            'class' => 'btn btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'data-url' => Url::to(['create']),
                            'data-pjax' => '0',
                        ])
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>
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
            ?>
            <?php
            Modal::begin([
                'id' => 'modal',
                'header' => '<h4 class="modal-title">MODULO: Sprints</h4>',
            ]);

            echo "<div class='well'></div>";

            Modal::end();
            ?>
        </div>           
    </div>
</div>
