<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SprintUsuarios */

$this->title = 'Create Sprint Usuarios';
$this->params['breadcrumbs'][] = ['label' => 'Sprint Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-usuarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
