<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DepartamentosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departamentos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <div class="departamentos-index">
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'id' => 'Departamentos-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => 'default',
                    'heading' => '<i class="fa fa-building"></i> Departamentos'
                ],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    ['label' => 'ID', 'attribute' => 'departamento_id', 'filter' => FALSE],
                    ['label' => 'Descripcion', 'attribute' => 'descripcion', 'filter' => TRUE],
                    [
                        'label' => 'Estado',
                        'attribute' => 'estado',
                        'value' => function ($data) {

                            if($data['estado'] == 0){
                                return 'Inactivo';
                            }
                            if($data['estado'] == 1){
                                return 'Activo';
                            }
                            return 'Error';
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo'],['class'=>'form-control','prompt' => '']),
                        'contentOptions' => ['style' => 'width:5px;'],
                    ],
                    [
                        'class'=>'kartik\grid\ActionColumn',
                        'template' => '{update}{delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                            'id' => 'activity-index-link',
                                            'title' => Yii::t('yii', 'Actualizar'),
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modal',
                                            'data-url' => Url::to(['update', 'id' => $model->departamento_id]),
                                            'data-pjax' => '0',
                                ]);
                            },
                        ]
                    ],            
                ],                 
                'toolbar' => [
                        ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Departamento', '#', [
                            'id' => 'activity-index-link',
                            'class' => 'btn btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'data-url' => Url::to(['create']),
                            'data-pjax' => '0',
                        ])
                    ],
                ],
            ]);
            ?>
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
                'header' => '<h4 class="modal-title">MODULO: Departamentos</h4>'
            ]);
            echo "<div class='well'></div>";
            Modal::end();
            ?>
        </div>
    </div>
</div>




