<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TiposUsuarios */

$this->title = 'Crear Tipos De Usuarios';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-usuarios-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
