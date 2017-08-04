<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TiposDocumentos */

$this->title = 'Actualizar Tipos De Documentos('.$model->documento_id.')';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->documento_id, 'url' => ['view', 'id' => $model->documento_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipos-documentos-update">

    <h3><?= Html::encode($this->title) ?></h3>
    <br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
