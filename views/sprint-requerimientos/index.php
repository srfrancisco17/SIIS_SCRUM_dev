<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintRequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprint Requerimientos';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php

    $consulta = \app\models\Sprints::find()
            ->select('sprint_id, sprint_alias')
            ->where(['sprint_id' => $sprint_id])
            ->one();


    $sprint_alias = '<small> Sprint Sin Alias </small>';
    if (!empty($consulta->sprint_alias)){
        $sprint_alias = $consulta->sprint_alias;
    }
?>
<div class="row"> 
    <div class="col-md-10">
        <div class="sprint-requerimientos-index">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'sprint-usuarios-form',
                        'enableAjaxValidation' => true,
                        'enableClientScript' => true,
                        'enableClientValidation' => true,
            ]);
            ?>
            <?php ActiveForm::end() ?>
            <?=
            GridView::widget([
                'id' => 'sprintRequerimiento-grid',
                'dataProvider' => $sprintRequerimientosDataProvider,
                'filterModel' => $sprintRequerimientosSearchModel,
                'autoXlFormat' => true,
                'panel' => [
                    'heading' => '<i class="glyphicon glyphicon-expand"></i> <b> ID:' . $sprint_id . '</b> - '.$sprint_alias,
                    'type' => GridView::TYPE_DEFAULT,
                    'footer' => '',
                ],
                'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                    //'sprint_id',
                    [
                        'attribute' => 'requerimiento_id',
                        'label' => 'Requerimiento',
                        'value' => 'requerimiento.requerimiento_titulo',
                        'filter' => FALSE,
                    ],
                        [
                        'label' => 'Usuario Asignado',
                        'attribute' => 'usuario_asignado',
                        'value' => 'usuarioAsignado.nombres',
                        'filter' => FALSE,
                    ],
                        [
                        'attribute' => 'tiempo_desarrollo',
                        'filter' => FALSE,
                    ],
                    //'usuario_asignado',
                    //'tiempo_desarrollo',
                    'prioridad',
                        [
                        'label' => 'Estado',
                        'attribute' => 'estado',
                        'filter' => Html::activeDropDownList($sprintRequerimientosSearchModel, 'estado', ['2' => 'En Espera', '3' => 'En progreso', '4' => 'Terminado', '5' => 'No Cumplida'], ['class' => 'form-control', 'prompt' => '']),
                        //'width' => '100px',
                        'value' => function ($data) {
                            //print_r($data);
                            //Condicionales que me permiten hacer una equivalencia de valores numericos en textos
                            if ($data['estado'] == 2) {
                                return 'En Espera';
                            }
                            if ($data['estado'] == 3) {
                                return 'En Progreso';
                            }
                            if ($data['estado'] == 4) {
                                return 'Terminado';
                            }
                            if ($data['estado'] == 5) {
                                return 'No Cumplida';
                            }
                            return 'Error';
                        },
                        'contentOptions' => ['style' => 'width:100px;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}{delete}{sprints}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                            'id' => 'activity-index-link',
                                            'title' => Yii::t('yii', 'Actualizar'),
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modal',
                                            'data-url' => Url::to(['update', 'sprint_id' => $model->sprint_id, 'requerimiento_id' => $model->requerimiento_id]),
                                            'data-pjax' => '0',
                                ]);
                            },
                        ]
                    ],
                ],
                'pjax' => true,
                'toolbar' => [
                        ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Asociar Requerimiento', '#', [
                            'id' => 'activity-index-link',
                            'class' => 'btn btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'data-url' => Url::to(['create', 'sprint_id' => $sprint_id]),
                            'data-pjax' => '0',
                        ]),
                    ],
                ],
            ]);
            ?>
            <!-- -->
            <?php
            $this->registerJs(
                    "$(document).on('click', '#activity-index-link', (function() {
                    $.get(
                        $(this).data('url'),
                        function (data) {
                            $('.modal-body').html(data);
                            $('#modal').modal();
                        }
                    );
                }));
                $(document).on('click', '#activity-index-link2', (function() {
                    $.get(
                        $(this).data('url'),
                        function () {
                            $('.modal-body2').html();
                            $('#modal2').modal();
                        }
                    );
                }));
                $(document).on('click', '#activity-index-link3', (function() {
                    $.get(
                        $(this).data('url'),
                        function () {
                            $('.modal-body3').html();
                            $('#modal3').modal();
                        }
                    );
                }));                
                    
                $('#enviar_datos').click(function(e){
                    var keys = $('#kv-grid-listausuarios').yiiGridView('getSelectedRows');
                    var form = $('#sprint-usuarios-form');

                    $.post(
                        form.action = 'index.php?r=sprint-requerimientos/peticion1&id='+'$sprint_id'+'&k='+keys,
                        form.serialize()
                    ).done(function(result) {
                        form.parent().html(result.message);
                        $.pjax.reload({container:'#sprint-usuarios-form'}); 

                    });

                    e.preventDefault();    
                    return false;

                });
                
                $('#enviar_datos2').click(function(e){
                    var keys2 = $('#kv-grid3').yiiGridView('getSelectedRows');
                    var form = $('#sprint-usuarios-form');
                    
                    var id_usuarios = new Array();
                    for (i = 0; i < keys2.length; i++) {
                        id_usuarios.push(keys2[i].usuario_id);
                    };
                    
                    $.post(
                        form.action = 'index.php?r=sprint-requerimientos/peticion2&id='+'$sprint_id'+'&k='+id_usuarios.toString(),
                        form.serialize()
                    ).done(function(result) {
                        form.parent().html(result.message);
                        $.pjax.reload({container:'#sprint-usuarios-form'}); 

                    });

                    e.preventDefault();    
                    return false;

                    console.log(id_usuarios.toString());

                });
            ");
            ?>
            <?php
            Modal::begin([
                'id' => 'modal',
                'header' => '<h4 class="modal-title">MODULO: Sprint-Requerimientos</h4>',
            ]);

            echo "<div class='well'></div>";

            Modal::end();
            ?>
        </div>
    </div> 
    <div class="row">
        <div class="col-lg-2">
            <?php
            echo Html::a('<span class="badge bg-blue">' . $usuariosDataProvider->getCount() . '</span><i class="fa fa-users"></i> Agregar Desarrollador', '#', [
                'id' => 'activity-index-link2',
                'class' => 'btn btn-app',
                'data-toggle' => 'modal2',
                'data-target' => '#modal2', //Aqui!!!
                //'data-url' => Url::to(['listausuarios']),
                //'data-url' => Url::to(['listausuarios', 'comite_id' => $comite_id ]),
                'data-pjax' => '0',
            ])
            ?>
        </div>
        <style>
            #activity-index-link2{
                color: #007DC8;
            }
            #activity-index-link3{
                color: #dd4b39;
            }
        </style>
        <div class="col-lg-2">
            <?php
            echo Html::a('<span class="badge bg-red">' . $sprintUsuariosDataProvider->getCount() . '</span><i class="fa fa-user-times"></i> Eliminar Desarrollador', '#', [
                'id' => 'activity-index-link3',
                'class' => 'btn btn-app',
                'data-toggle' => 'modal3',
                'data-target' => '#modal3', //Aqui!!!
                //'data-url' => Url::to(['listausuarios']),
                //'data-url' => Url::to(['listausuarios', 'comite_id' => $comite_id ]),
                'data-pjax' => '0',
            ])
            ?>
        </div>
    </div>
    <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <!--ASIGNAMOS UN ID A ESTE DIV -->
        <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-header bg-blue-gradient">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Desarrolladores</h4>
                </div>
                <div class="modal-body2">
                    <?=
                    GridView::widget([
                        'id' => 'kv-grid-listausuarios',
                        'dataProvider' => $usuariosDataProvider,
                        'filterModel' => FALSE,
                        'columns' => [
                                ['attribute' => 'usuario_id', 'width' => '100px', 'filter' => FALSE],
                                [
                                'label' => 'Nombres',
                                'attribute' => 'nombres',
                                'filter' => FALSE
                            ],
                                [
                                'label' => 'Apellidos',
                                'attribute' => 'apellidos',
                                'filter' => FALSE
                            ],
                                [
                                'class' => 'kartik\grid\CheckboxColumn',
                                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                            ],
                        ],
                        'pjax' => true,
                    ])
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <input type="submit"  class="btn btn-primary" name="enviar_datos" id="enviar_datos" value="Agregar">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal 3 -->
    <div class="modal fade" id="modal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <!--ASIGNAMOS UN ID A ESTE DIV -->
        <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-header bg-red-gradient">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Eliminar Desarrolladores</h4>
                </div>
                <div class="modal-body3">
                    <?=
                    GridView::widget([
                        'id' => 'kv-grid3',
                        'dataProvider' => $sprintUsuariosDataProvider,
                        'filterModel' => FALSE,
                        'columns' => [
                                ['attribute' => 'usuario_id', 'width' => '100px', 'filter' => FALSE],
                                [
                                'label' => 'Nombres',
                                'attribute' => 'usuario.nombres',
                                'filter' => FALSE
                            ],
                                [
                                'label' => 'Apellidos',
                                'attribute' => 'usuario.apellidos',
                                'filter' => FALSE
                            ],
                                [
                                'class' => 'kartik\grid\CheckboxColumn',
                                'rowSelectedClass' => GridView::TYPE_DANGER,
                            ],
                        ],
                        'pjax' => true,
                    ])
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <input type="submit"  class="btn btn-danger" name="enviar_datos2" id="enviar_datos2" value="Eliminar">
                </div>
            </div>
        </div>
    </div>
</div>

