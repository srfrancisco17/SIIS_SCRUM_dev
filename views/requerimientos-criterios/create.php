<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosCriterios */

$this->title = 'Create Requerimientos Criterios';
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos Criterios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requerimientos-criterios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
