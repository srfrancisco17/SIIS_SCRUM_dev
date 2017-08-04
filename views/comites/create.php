<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Comites */

$this->title = 'Create Comites';
$this->params['breadcrumbs'][] = ['label' => 'Comites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comites-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
