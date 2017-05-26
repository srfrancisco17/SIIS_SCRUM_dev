<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\SprintRequerimientosTareas;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintRequerimientosTareasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprint Requerimientos Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-tareas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <!--
    <p>
        <?= Html::a('Create Sprint Requerimientos Tareas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    -->
    <div class="row">
        <div class="col-lg-12">
            <?php
                echo '<pre>';
                print_r($dataProvider->models[0]->requerimiento->requerimiento_titulo);
                echo '--------------------------';
                print_r($dataProvider->models[0]->requerimiento->requerimiento_descripcion);
                echo '--------------------------';
                print_r($dataProvider->models[0]->requerimiento->requerimiento_justificacion);
                echo '--------------------------';
                print_r($dataProvider->models[0]->requerimiento->observaciones);
                echo '</pre>';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">

            <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'tarea_id',
                //'sprint_id',
                //'requerimiento_id',
                'tarea_titulo',
                'tarea_descripcion:ntext',
                // 'estado',
                // 'tiempo_desarrollo',

                ['class' => 'yii\grid\ActionColumn'],
            ],
            ]); ?>

        </div>
        <div class="col-lg-6">
        <?php
            $model = new SprintRequerimientosTareas();
        ?>
        <?php Pjax::begin(); ?>
            
        <?= Html::a("Refresh", ['sprint-requerimientos-tareas/create','model' => $model], ['class' => 'btn btn-lg btn-primary']);?>
            
        <h1>Current time: <?= $time ?></h1>
        
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
