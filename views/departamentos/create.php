<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Departamentos */

$this->title = 'Crear Departamentos';
$this->params['breadcrumbs'][] = ['label' => 'Departamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departamentos-create">

    <h3><?= Html::encode($this->title) ?></h3>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
