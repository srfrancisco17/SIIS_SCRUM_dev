<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComitesAsistentes */

$this->title = 'Update Comites Asistentes: ' . $model->comite_id;
$this->params['breadcrumbs'][] = ['label' => 'Comites Asistentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->comite_id, 'url' => ['view', 'comite_id' => $model->comite_id, 'usuario_id' => $model->usuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comites-asistentes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
