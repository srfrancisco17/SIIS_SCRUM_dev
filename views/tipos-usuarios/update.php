<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TiposUsuarios */

$this->title = 'Actualizar Tipos De Usuarios('.$model->tipo_usuario_id.')';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tipo_usuario_id, 'url' => ['view', 'id' => $model->tipo_usuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipos-usuarios-update">

    <h3><?= Html::encode($this->title) ?></h3>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
