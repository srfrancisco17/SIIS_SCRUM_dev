<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'default',
            'heading' => '<i class="fa fa-users"></i></i> Usuarios'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','contentOptions' => ['style' => 'width:10px;']],
            //'usuario_id',
            //'num_documento',
            //'tipo_documento',
            //'nombres',
            //'apellidos',
            [
                'label' => 'Documento',
                'attribute' => 'num_documento',
                'width' => '100px'
            ],
            [
                'label' => 'Tipo De Documento',
                'attribute' => 'tipo_documento',
                //'value' => 'tipoDocumento.descripcion',
                'width' => '10px'
            ],
            [
                'label' => 'Nombres',
                'attribute' => 'nombres',
                'width' => '150px'
            ],
            [
                'label' => 'Apellidos',
                'attribute' => 'apellidos',
                'width' => '150px'
            ],
            /*
            [
                'label' => 'Correo Electronico',
                'attribute' => 'correo',
                'width' => '150px'
            ],
            */
            [            
                'attribute' => 'tipo_usuario',
                'contentOptions' => ['style' => 'width:150px;'],
                'value' => 'tipoUsuario.descripcion',
                //'filter' => Html::activeDropDownList($searchModel, 'tipo_usuario', ArrayHelper::map(app\models\TiposUsuarios::find()->asArray()->all(), 'tipo_usuario_id', 'descripcion'),['class'=>'form-control','prompt' => 'Seleccione']),
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(app\models\TiposUsuarios::find()->asArray()->all(), 'tipo_usuario_id', 'descripcion'),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
                'filterInputOptions' => [
                    'placeholder' => '',
                ],
            ],
            
            [            
                'attribute' => 'departamento',
                'contentOptions' => ['style' => 'width:150px;'],
                'value' => 'departamento0.descripcion',
                //'filter' => Html::activeDropDownList($searchModel, 'tipo_usuario', ArrayHelper::map(app\models\TiposUsuarios::find()->asArray()->all(), 'tipo_usuario_id', 'descripcion'),['class'=>'form-control','prompt' => 'Seleccione']),
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(app\models\Departamentos::find()->asArray()->all(), 'departamento_id', 'descripcion'),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
                'filterInputOptions' => [
                    'placeholder' => '',
                ],
            ],
            [
                'label' => 'Estado',
                'attribute' => 'estado',
                'value' => function ($data) {
                    //print_r($data);

                    if($data['estado'] == 0){
                        return 'Inactivo';
                    }
                    if($data['estado'] == 1){
                        return 'Activo';
                    }
                    return 'Error';
                },
                'filter' => Html::activeDropDownList($searchModel, 'estado', ['0'=>'Inactivo', '1'=>'Activo'],['class'=>'form-control','prompt' => '']),
                'contentOptions' => ['style' => 'width:5px;'],
            ],
            // 'descripcion',
            // 'correo',
            // 'telefono',
            // 'contrasena',
            // 'departamento',
            // 'tipo_usuario',
            // 'color',
            // 'estado',
            ['class' => 'yii\grid\ActionColumn','contentOptions' => ['style' => 'width:8%;'],],
        ],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Usuarios', ['create'], ['class' => 'btn btn-success'])
            ],

        ],

    ]); ?>
</div>
