<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientos */

$this->title = $model->sprint_id;
$this->params['breadcrumbs'][] = ['label' => 'Sprint Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id], [
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
            'sprint_id',
            'requerimiento_id',
            'usuario_asignado',
            'tiempo_desarrollo',
            'estado',
        ],
    ]) ?>

</div>
