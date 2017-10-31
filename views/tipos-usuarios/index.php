<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
//use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TiposUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\ValorHelpers;

$this->title = 'Tipos Usuario';

// $this->title = $titulo;

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?php Pjax::begin(); ?>
        <?=
        GridView::widget([
            'id' => 'TipoUsuarios-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => 'default',
                'heading' => '<i class="fa fa-user-secret"></i> Tipos Usuario'
            ],
            'columns' => [
                ['label' => 'ID','attribute' => 'tipo_usuario_id','filter' => FALSE],
                ['label' => 'Descripcion','attribute' => 'descripcion','filter' => FALSE],
                [
                    'label' => 'Estado',
                    'attribute' => 'estado',
                    'filter' => FALSE,
                    'value' => function ($data) {
                        if($data['estado'] == 0){
                            return "Inactivo";
                        }
                        if($data['estado'] == 1){
                            return "Activo";
                        }
                            return "Error";
                        },
                ],
                [
                    'class'=>'kartik\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                        //'id' => 'link_modal1',
                                        'class' => 'link_modal',
                                        'title' => Yii::t('yii', 'Actualizar'),
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal',
                                        'data-url' => Url::to(['update', 'id' => $model->tipo_usuario_id]),
                                        'data-pjax' => '0',
                                        'data-opcion' => '0',
                            ]);
                        },
                    ]
                ],
            ],
            'toolbar' => [
                    ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Tipo De Usuario', '#', [
                        'id' => 'link_modal2',
                        'class' => 'btn btn-success link_modal',
                        'data-toggle' => 'modal',
                        'data-target' => '#modal',
                        'data-url' => Url::to(['create']),
                        'data-pjax' => '0',
                        'data-opcion' => '1',
                    ])
                ],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
        <?php

            ValorHelpers::modalFormularios($this->title);

        ?>
    </div>
</div>