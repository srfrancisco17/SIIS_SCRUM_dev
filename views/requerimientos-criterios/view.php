<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosCriterios */

$this->title = $model->requerimiento_id;
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos Criterios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requerimientos-criterios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'requerimiento_id' => $model->requerimiento_id, 'criterio_id' => $model->criterio_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'requerimiento_id' => $model->requerimiento_id, 'criterio_id' => $model->criterio_id], [
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
            'requerimiento_id',
            'criterio_id',
            'valor',
        ],
    ]) ?>

</div>
