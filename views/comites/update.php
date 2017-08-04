<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comites */

$this->title = 'Actualizar Comite: ' . $model->comite_id;
$this->params['breadcrumbs'][] = ['label' => 'Comites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->comite_id, 'url' => ['view', 'id' => $model->comite_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comites-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
