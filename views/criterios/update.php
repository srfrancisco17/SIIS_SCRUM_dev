<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Criterios */

$this->title = 'Update Criterios: ' . $model->criterio_id;
$this->params['breadcrumbs'][] = ['label' => 'Criterios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->criterio_id, 'url' => ['view', 'id' => $model->criterio_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="criterios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
