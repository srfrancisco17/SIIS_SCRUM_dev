<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ComitesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Comites';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #3c8dbc;
        border-color: #ddd;
    }
</style>
<div class="comites-index">
<section id="introduction">
  <p class="lead">
    <b>COMITES</b> un grupo de individuos que trabajan en conjunto para resolver alguna problemática o llevar a cabo un proyecto.
  </p>
</section>
    
    <div class="row">
        <div class="col-lg-12"> 
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'id' => 'comites-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => 'default',
                    //'heading' => '<h3 class="panel-title"><i class="fa fa-book"></i>  Comites'
                    'heading' => Yii::$app->user->identity->tipo_usuario == 1 ? '<h3 class="panel-title"><i class="fa fa-book"></i>  Comites ADMIINISTRADOR' : '<h3 class="panel-title"><i class="fa fa-book"></i>  Comites DESCONOCIDO', 
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'comite_id',
                    'comite_alias',
                    [
                        'attribute' => 'fecha',
                        //'filter' => 'PONER FILTRO!!!!'
                        'filterType'=> GridView::FILTER_DATE, 
                        'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Seleccione Fecha'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose'=>true,
                            ]
                        ],
                    ],
                    ['attribute'=>'hora_desde','filter' =>false],
                    ['attribute'=>'hora_hasta','filter' =>false],
                    'lugar',
                    [
                        'label' => 'Estado',
                        'attribute' => 'estado',
                        'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo'],['class'=>'form-control','prompt' => '']),
                        'width' => '10px',
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
                        'class'=>'kartik\grid\ActionColumn',
                        //'template' => '{update}{delete}{user}',
                        'template' => Yii::$app->user->identity->tipo_usuario == 1 ? '{update}{delete}{user}' : '{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                            'id' => 'activity-index-link',
                                            'title' => Yii::t('yii', 'Update'),
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modal',
                                            'data-url' => Url::to(['update', 'id' => $model->comite_id]),
                                            'data-pjax' => '0',
                                ]);
                            },
                            'user' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-user"></span>', Url::to(['comites-asistentes/index', 'comite_id' => $model->comite_id]), [
                                            'id' => 'activity-index-link2',
                                            'title' => Yii::t('yii', 'Usuarios'),
                                ]);
                            },
                        ]
                    ],
                ],
                'toolbar' => [
                        ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Comites', '#', [
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
                'header' => '<h4 class="modal-title">Modulo Comite</h4>'
                
            ]);
            echo "<div class='well'></div>";
            Modal::end();
            ?>
        </div>
    </div>
</div>