<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */

$this->title = $model->tarea_id;
$this->params['breadcrumbs'][] = ['label' => 'Sprint Requerimientos Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-tareas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tarea_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tarea_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tarea_id',
            'sprint_id',
            'requerimiento_id',
            'tarea_titulo',
            'tarea_descripcion:ntext',
            'estado',
            'tiempo_desarrollo',
        ],
    ]) ?>

</div>
