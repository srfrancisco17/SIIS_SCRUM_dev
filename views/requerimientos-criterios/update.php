<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosCriterios */

$this->title = 'Update Requerimientos Criterios: ' . $model->requerimiento_id;
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos Criterios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->requerimiento_id, 'url' => ['view', 'requerimiento_id' => $model->requerimiento_id, 'criterio_id' => $model->criterio_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="requerimientos-criterios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
