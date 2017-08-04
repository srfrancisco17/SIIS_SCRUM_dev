<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ComitesAsistentes */

$this->title = 'Create Comites Asistentes';
$this->params['breadcrumbs'][] = ['label' => 'Comites Asistentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comites-asistentes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
