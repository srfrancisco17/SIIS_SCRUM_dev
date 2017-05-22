<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->num_documento;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-view">
    
    <div class="row">
    <div class="requerimientos-view">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Usuario</h3>
                </div>
                <div class="box-body">         
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        ['label'=> 'Usuario ID:','attribute' => 'usuario_id'],
                        ['label'=> 'Numero Documento:','attribute' => 'num_documento'],
                        [                      
                        'label' => 'Tipo Documento:',
                        'value' => $model->tipoDocumento->descripcion,
                        ],
                        ['label'=> 'Nombres:','attribute' => 'nombres'],
                        ['label'=> 'Apellidos:','attribute' => 'apellidos'],
                        ['label'=> 'Descripcion:','attribute' => 'descripcion'],
                        ['label'=> 'Correo:','attribute' => 'correo'],
                        ['label'=> 'Telefono:','attribute' => 'telefono'],
                        ['label'=> 'Contraseña:','attribute' => 'contrasena'],
                        [                      
                        'label' => 'Departamento',
                        'value' => $model->departamento0->descripcion,
                        ],
                        [                      
                        'label' => 'Tipo Usuario',
                        'value' => $model->tipoUsuario->descripcion,
                        ],
                        ['label'=> 'Color:','attribute' => 'color'],
                        [
                            'label'=> 'Estado:',
                            'attribute' => 'estado',
                            'value' => function ($model) {
                                if($model['estado'] == 0){
                                    return 'Inactivo';
                                }
                                if($model['estado'] == 1){
                                    return 'Activo';
                                }
                                return 'Error';
                            },
                        ],
                    ],
                ]) ?>
                </div>
                <div class="box-footer with-border">
                    <?= Html::a('Actualizar', ['update', 'id' => $model->usuario_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Eliminar', ['delete', 'id' => $model->usuario_id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => '¿Está seguro de eliminar este elemento?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
