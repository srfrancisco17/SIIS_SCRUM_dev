<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientos */

$this->title = 'Crear Sprint Requerimientos';
$this->params['breadcrumbs'][] = ['label' => 'Sprint Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-create">

    <?= $this->render('_form', [
        'model' => $model,
        'sprint_id' => $sprint_id,
    ]) ?>

</div>
