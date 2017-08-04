<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ComitesAsistentes */

$this->title = $model->comite_id;
$this->params['breadcrumbs'][] = ['label' => 'Comites Asistentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comites-asistentes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'comite_id' => $model->comite_id, 'usuario_id' => $model->usuario_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'comite_id' => $model->comite_id, 'usuario_id' => $model->usuario_id], [
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
            'comite_id',
            'usuario_id',
            'estado',
            'sw_responsable',
            'observacion',
        ],
    ]) ?>

</div>
