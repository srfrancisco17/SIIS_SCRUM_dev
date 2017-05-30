<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Requerimientos */

$this->title = $model->requerimiento_id.' - '.$model->requerimiento_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<h1><?= Html::encode($this->title) ?></h1>-->
<div class="row">
    <div class="requerimientos-view">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Requerimiento</h3>
                </div>
                
                <div class="box-body">         
                    <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'requerimiento_id',
                        'comite_id',
                        'requerimiento_titulo',
                        'requerimiento_descripcion:html',
                        'requerimiento_justificacion:html',
                        //'usuario_solicita',
                        [                      
                        'label' => 'Usuario Que Solicita',
                        'value' => $model->usuarioSolicita->nombres.' '.$model->usuarioSolicita->apellidos,
                        ],
                        [                      
                        'label' => 'Departamento Que Solicita', 
                        'value' => empty($model->departamentoSolicita->descripcion) ? '' : $model->departamentoSolicita->descripcion,
                        ],
                        'observaciones:html',
                        'fecha_requerimiento',
                        'estado',
                    ],
                ]) ?>     
                </div>
                <div class="box-footer">
                    <?= Html::a('Actualizar', ['update', 'id' => $model->requerimiento_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Eliminar', ['delete', 'id' => $model->requerimiento_id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Esta Seguro De Eliminar Este Requerimiento?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
