<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\SprintRequerimientosSearch;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprint Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #ff851b;
        border-color: #ddd;
    }
    /*
    .kv-expanded-row{
        background-color: red;
    }
    */

</style>

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--<?= Html::a('Create Sprint Usuarios', ['create'], ['class' => 'btn btn-success']) ?>-->
    </p>
   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-undo"></i> Sprints-Usuarios </h3>',
            'type' => GridView::TYPE_DEFAULT,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\ExpandRowColumn',

                'value' => function($model, $key, $index, $column){
                        return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column){

                    $searchModel2 = new SprintRequerimientosSearch();
                    $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams,$model->sprint_id,2);

                return Yii::$app->controller->renderPartial('_index2', [
                        'searchModel2' => $searchModel2,
                        'dataProvider2' => $dataProvider2,
                        ]);

                },
            ],
            [
                'attribute' => 'sprint_id',
                'contentOptions' => ['style' => 'width:0px;'],
                'filter'=>FALSE
            ],                              
            [
                'attribute' => 'sprintName'
            ],
            //'sprint_id',
            //'usuario_id',
            //'horas_desarrollo',
            //'observacion:ntext',
            [
              'attribute' => 'sprint.fecha_desde', 
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                'attribute' => 'estado',
            ],
            [
                'class'=>'kartik\grid\ActionColumn',
                'template' => '{requerimientos}',
                'buttons' => [
                    'requerimientos' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-table"></span>', Url::to(['sprint-usuarios/kanban','sprint_id'=>$model->sprint_id]), [
                                'id' => 'activity-index-link2',
                                'title' => Yii::t('yii', 'Tablero Kanban'),
                    ]);
                },
                ]
            ],
        ],
    ]); ?>
