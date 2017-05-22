<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TiposDocumentosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos Documentos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <div class="tipos-documentos-index">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'id' => 'TipoDocumentos-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => 'default',
                    'heading' => '<i class="fa fa-folder"></i> Tipos Documentos'
                ],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    ['label' => 'ID','attribute' => 'documento_id', 'filter' => FALSE],
                    ['label' => 'Descripcion','attribute' => 'descripcion', 'filter' => FALSE],
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
                                            'data-url' => Url::to(['update', 'id' => $model->documento_id]),
                                            'data-pjax' => '0',
                                ]);
                            },
                        ]
                    ],
                ],
                'toolbar' => [
                        ['content' =>       
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Tipo De Documentos', '#', [
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
                'header' => '<h4 class="modal-title">MODULO: Tipo De Documentos</h4>',
            ]);
            echo "<div class='well'></div>";
            Modal::end();
            ?>
        </div>
    </div>
</div>
