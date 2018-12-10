<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CriteriosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Criterios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="criterios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Criterios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'criterio_id',
            'descripcion',
            'descripcion_abreviada',
            'estado',
            'orden',
            //'valor',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
