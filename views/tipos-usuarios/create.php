<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TiposUsuarios */

$this->title = 'Crear Tipos De Usuarios';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-usuarios-create">

    <h3><?= Html::encode($this->title) ?></h3>
    <br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
