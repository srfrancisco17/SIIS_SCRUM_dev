<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SprintUsuarios */

$this->title = 'Update Sprint Usuarios: ' . $model->sprint_id;
$this->params['breadcrumbs'][] = ['label' => 'Sprint Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sprint_id, 'url' => ['view', 'sprint_id' => $model->sprint_id, 'usuario_id' => $model->usuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sprint-usuarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
