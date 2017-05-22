<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TiposDocumentos */

$this->title = 'Crear Tipos De Documentos';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-documentos-create">

    <!--<h3><?= Html::encode($this->title) ?></h3>-->
    <br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
