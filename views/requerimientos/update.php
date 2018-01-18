<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Requerimientos */

$this->title = 'Actualizar Requerimiento: [' . $model->requerimiento_id.']';
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->requerimiento_id.' - '.$model->requerimiento_titulo, 'url' => ['view', 'id' => $model->requerimiento_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>


<?= $this->render('_form', [
    'model' => $model,
    'RT_searchModel' => $RT_searchModel,
    'RT_dataProvider' => $RT_dataProvider,
    'PI_searchModel' => $PI_searchModel,
    'PI_dataProvider' => $PI_dataProvider,
    'PUI_searchModel' => $PUI_searchModel,
    'PUI_dataProvider' => $PUI_dataProvider,
    'RI_model' => $RI_model,
    'sprint_id' => $sprint_id,
]) ?>


