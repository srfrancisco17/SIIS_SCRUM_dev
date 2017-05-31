<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientosTareas */

$this->title = 'Create Sprint Requerimientos Tareas';
$this->params['breadcrumbs'][] = ['label' => 'Sprint Requerimientos Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-requerimientos-tareas-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
