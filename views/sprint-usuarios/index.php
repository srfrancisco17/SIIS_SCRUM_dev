<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\SprintRequerimientosSearch;

$this->title = 'Mis Sprints';
$this->params['breadcrumbs'][] = $this->title;
?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #3c8dbc;
        border-color: #ddd;
    }
</style>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar' => FALSE,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-undo"></i> SPRINTS </h3>',
            'type' => GridView::TYPE_DEFAULT,
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width:2px;'],
            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',

                'value' => function($model, $key, $index, $column){
                        return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column){

                $searchModel2 = new SprintRequerimientosSearch();
                $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams,$model->sprint_id,2);
                $dataProvider2->sort = false;

                return Yii::$app->controller->renderPartial('_index2', [
                        'searchModel2' => $searchModel2,
                        'dataProvider2' => $dataProvider2,
                        ]);

                },
            ],                             
            [
                'label' => 'SPRINT ALIAS',
                'attribute' => 'sprintName'
            ],
            [
                'label' => 'FECHA DESDE',
                'attribute' => 'sprint.fecha_desde', 
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                'label' => 'FECHA HASTA',
                'attribute' => 'sprint.fecha_hasta', 
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                'label' => 'ESTADO',
                'attribute' => 'sprint.estado',
                'value' => function ($data) {
                        if($data['sprint']->estado == 0){
                            return 'Inactivo';
                        }
                        if($data['sprint']->estado == 1){
                            return 'Activo';
                        }
                        if($data['sprint']->estado == 4){
                            return 'Terminado';
                        }
                        return 'Error';
                 },
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                'header' => 'ACCIONES',
                'class'=>'kartik\grid\ActionColumn',
                'template' => '{kanban}',
                'buttons' => [
                    'kanban' => function ($url, $model, $key) {
                     
                        if ($model->sprint->estado != 0){
                            return Html::a('<span class="fa fa-table"></span>', Url::to(['sprint-usuarios/kanban','sprint_id'=>$model->sprint_id]), [
                                    'id' => 'activity-index-link2',
                                    'title' => Yii::t('yii', 'Tablero Kanban'),
                            ]);
                        }else{
                            return 'No Disponible';
                        } 
                },
                ]
            ],
        ],
    ]); ?>
