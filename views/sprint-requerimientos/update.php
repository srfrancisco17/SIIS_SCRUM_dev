<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientos */

$this->title = 'Update Sprint Requerimientos: ' . $model->sprint_id;
$this->params['breadcrumbs'][] = ['label' => 'Sprint Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sprint_id, 'url' => ['view', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sprint-requerimientos-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
