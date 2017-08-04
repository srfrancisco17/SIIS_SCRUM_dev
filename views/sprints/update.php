<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Sprints */

$this->title = 'Update Sprints: ' . $model->sprint_id;
$this->params['breadcrumbs'][] = ['label' => 'Sprints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sprint_id, 'url' => ['view', 'id' => $model->sprint_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sprints-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
