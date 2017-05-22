<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TiposUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <div class="tipos-usuarios-index">
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'id' => 'TipoUsuarios-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => 'default',
                    'heading' => '<i class="fa fa-user-secret"></i> Tipos Usuarios'
                ],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    ['label' => 'ID','attribute' => 'tipo_usuario_id','filter' => FALSE],
                    ['label' => 'Descripcion','attribute' => 'descripcion','filter' => FALSE],
                    [
                        'label' => 'Estado',
                        'attribute' => 'estado',
                        'filter' => FALSE,
                        'value' => function ($data) {
                        //print_r($data);

                        if($data['estado'] == 0){
                            return 'Inactivo';
                        }
                        if($data['estado'] == 1){
                            return 'Activo';
                        }
                        return 'Error';
                        },
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
                                            'data-url' => Url::to(['update', 'id' => $model->tipo_usuario_id]),
                                            'data-pjax' => '0',
                                ]);
                            },
                        ]
                    ],
                ],
                'toolbar' => [
                        ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Tipo De Usuario', '#', [
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
                'header' => '<h4 class="modal-title">MODULO: Tipo De Usuario</h4>',
            ]);
            echo "<div class='well'></div>";
            Modal::end();
            ?>
        </div>
    </div>
</div>