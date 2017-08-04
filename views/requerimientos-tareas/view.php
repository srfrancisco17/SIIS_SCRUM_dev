<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosTareas */

$this->title = $model->tarea_id;
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requerimientos-tareas-view">

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
            'requerimiento_id',
            'tarea_titulo',
            'tarea_descripcion:ntext',
            'ultimo_estado',
            'tiempo_desarrollo',
            'fecha_terminado',
        ],
    ]) ?>

</div>
