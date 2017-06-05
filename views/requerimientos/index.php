<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requerimientos';
$this->params['breadcrumbs'][] = $this->title;
?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #f56954;
        border-color: #ddd;
    }
</style>
<div class="requerimientos-index">

    <div class="row">
        <div class="col-lg-12">
     
            <?php Pjax::begin(); ?>
            <?= 
                GridView::widget([
                'id' => 'requerimientos-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="fa fa-check-square-o"></i> Tabla Requerimientos </h3>',
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'bordered' => true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'requerimiento_id',
                    [
                        'attribute' => 'comite_id',
                        'label' => 'Comite',
                        'value' => 'comite.comite_alias',
                        'filter' => FALSE,
                    ],
                    //'comite_id',
                    'requerimiento_titulo',
                    //'requerimiento_descripcion:ntext',
                    //'requerimiento_justificacion:ntext',
                    // 'usuario_solicita',
                    // 'departamento_solicita',
                    // 'observaciones:ntext',
                    [
                        'attribute' => 'fecha_requerimiento',
                        'filterType'=> GridView::FILTER_DATE, 
                        'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Seleccione Fecha'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose'=>true,
                            ]
                        ],
                        'contentOptions' => ['style' => 'width:200px;'],
                    ],
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
                                case 2:
                                    echo "En Espera";
                                case 3:
                                    echo "En Progreso";
                                    break;
                                case 4:
                                    echo "Terminado";
                                    break;
                                default:
                                   echo "Error";
                            }
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo', '2' => 'En Espera', '3' => 'En Progreso', '4' => 'Terminado'],['class'=>'form-control','prompt' => '']),
                        'contentOptions' => ['style' => 'width:5px;'],
                    ],
                    ['class'=>'kartik\grid\ActionColumn'],
                ],
                'toolbar' => [
                        ['content' =>
                            Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Requerimientos', ['create'], ['class' => 'btn btn-success'])
                    ],
                ],

            ]); 
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
