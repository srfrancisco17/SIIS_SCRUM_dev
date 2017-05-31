<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */

$this->title = 'Update Sprint Requerimientos Tareas: ' . $model->tarea_id;
$this->params['breadcrumbs'][] = ['label' => 'Sprint Requerimientos Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tarea_id, 'url' => ['view', 'id' => $model->tarea_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sprint-requerimientos-tareas-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
