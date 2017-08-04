<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Departamentos */

$this->title = 'Actualizar Departamentos: ' . $model->departamento_id;
$this->params['breadcrumbs'][] = ['label' => 'Departamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->departamento_id, 'url' => ['view', 'id' => $model->departamento_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="departamentos-update">

    <h3><?= Html::encode($this->title) ?></h3>
    <br>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
