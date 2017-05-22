<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Sprints */

$this->title = 'ID Sprint('.$model->sprint_id.')';
$this->params['breadcrumbs'][] = ['label' => 'Sprints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">        
        <div class="sprints-view">

        <div class="panel panel-default">
            <div class="panel-heading bg-aqua">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'sprint_id',
                        'fecha_desde',
                        'fecha_hasta',
                        'horas_desarrollo',
                        'observaciones:ntext',
                        'estado',
                    ],
                ]) ?>
            </div>
            <div class="panel-footer bg-aqua">
            <!--<?= Html::a('Actualizar', ['update', 'id' => $model->sprint_id], ['class' => 'btn btn-primary']) ?>-->
            <?= Html::a('Eliminar', ['delete', 'id' => $model->sprint_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            </div>
        </div>

        

        </div>    
    </div>
</div>

