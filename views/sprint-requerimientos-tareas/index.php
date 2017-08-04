<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintRequerimientosTareasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprint Requerimientos Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-tareas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sprint Requerimientos Tareas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tarea_id',
            'sprint_id',
            'requerimiento_id',
            'estado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
