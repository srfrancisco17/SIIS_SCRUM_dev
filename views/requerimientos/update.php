<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Requerimientos */

$this->title = 'Actualizar Requerimiento: [' . $model->requerimiento_id.']';
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->requerimiento_id.' - '.$model->requerimiento_titulo, 'url' => ['view', 'id' => $model->requerimiento_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


