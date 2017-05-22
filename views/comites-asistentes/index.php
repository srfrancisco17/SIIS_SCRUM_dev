<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ComitesAsistentesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comites Asistentes';
$this->params['breadcrumbs'][] = ['label' => 'Comites', 'url' => ['comites/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$this->registerCss("
        #mdialTamanio{
                width: 80% !important;
            }
            
        ");
?>
<div class="comites-asistentes-index">    
    <?php
    $form = ActiveForm::begin([
                'id' => 'comite-asistente-form',
                'enableAjaxValidation' => true,
                'enableClientScript' => true,
                'enableClientValidation' => true,
    ]);
    ?>
    <?php ActiveForm::end() ?>
        <?= GridView::widget([
            'id' => 'ComitesAsistentes-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => 'default',
                'heading' => '<h3 class="panel-title"><i class="fa fa-book"></i>  Asistentes Al Comite'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'comite_id',
                [
                    'label' => 'Nombres',
                    'attribute' => 'usuario_id',
                    'value' => 'usuario.nombres',
                    'filter' => FALSE
                ],
                [
                    'label' => 'Apellidos',
                    'attribute' => 'usuario_id',
                    'value' => 'usuario.apellidos',
                    'filter' => FALSE
                ],
                ['label' => 'Estado','attribute' => 'estado','width' => '100px'],
                [
                    'label' => 'Responsable',
                    'attribute' => 'sw_responsable',
                    'value' => function ($data) {
                            if($data['sw_responsable'] == 1){
                                return 'Si';
                            }
                            return '';
                        },
                    'width' => '100px',
                    'filter' => FALSE
                ],
                'observacion',
                [
                    'class'=>'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                        'id' => 'activity-index-link',
                                        'title' => Yii::t('yii', 'Actualizar'),
                                        'data-toggle' => 'modal1 ',
                                        'data-target' => '#modal',
                                        'data-url' => Url::to(['update', 'comite_id' => $model->comite_id, 'usuario_id' => $model->usuario_id]),
                                        'data-pjax' => '0',
                            ]);
                        },
                    ]
                ],
            ],
            'pjax'=>true,
                    'toolbar' => [
                    ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Usuario', '#', [
                        'id' => 'activity-index-link2',
                        'class' => 'btn btn-success',
                        'data-toggle' => 'modal2',
                        'data-target' => '#modal2',//Aqui!!!
                        //'data-url' => Url::to(['listausuarios']),
                        'data-url' => Url::to(['listausuarios', 'comite_id' => $comite_id ]),
                        'data-pjax' => '0',
                    ])
                ],

            ],
        ]); 
    ?>
    
    <?php
    $this->registerJs("
        $(document).on('click', '#activity-index-link', (function() {
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
        
        $('#enviar_datos').click(function(e){
            var keys = $('#kv-grid-listausuarios').yiiGridView('getSelectedRows');
            var form = $('#comite-asistente-form');
            
            $.post(
                form.action = 'index.php?r=comites-asistentes/respuesta&id='+'$comite_id'+'&k='+keys,
                form.serialize()
            ).done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:'#comite-asistente-form'}); 

            });

            e.preventDefault();    
            return false;
            
        });
    ");
    ?>
    <?php

    Modal::begin([
        'id' => 'modal',
        'header' => '',
    ]);
    echo "<div class='well'></div>";
    Modal::end();
    
    ?> 
    <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <!--ASIGNAMOS UN ID A ESTE DIV -->
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
              <div class="modal-header bg-teal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar Usuarios Al Comite</h4>
              </div>
              <div class="modal-body2">
               <?= GridView::widget([
                   'id' => 'kv-grid-listausuarios',
                    'dataProvider' => $dataProvider2,
                    'filterModel' => $searchModel2,
                    'columns' => [
                        ['attribute' => 'usuario_id', 'width' => '100px'],
                        'nombres',
                        'apellidos',
                        [
                            'label' => 'Tipo Usuario',
                            'attribute' => 'usuario_id',
                            'value' => 'tipoUsuario.descripcion',
                            'filter' => Html::activeDropDownList($searchModel2, 'tipo_usuario', yii\helpers\ArrayHelper::map(app\models\TiposUsuarios::find()->asArray()->all(), 'tipo_usuario_id', 'descripcion'),['class'=>'form-control','prompt' => 'Seleccione']),
                        ], 
                        [
                            'label' => 'Departamento',
                            'attribute' => 'usuario_id',
                            'value' => 'departamento0.descripcion',
                            'filter' => Html::activeDropDownList($searchModel2, 'departamento', yii\helpers\ArrayHelper::map(app\models\Departamentos::find()->asArray()->all(), 'departamento_id', 'descripcion'),['class'=>'form-control','prompt' => 'Seleccione']),
                        ],   
                        [
                            'class' => 'kartik\grid\CheckboxColumn',
                            'rowSelectedClass' => GridView::TYPE_SUCCESS
                        ],
                    ],
                    'pjax' => true,
                ]) ?>
              </div>
              <div class="modal-footer bg-teal">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <input type="submit"  class="btn btn-success" name="enviar_datos" id="enviar_datos" value="Agregar Usuario">
              </div>
            </div>
          </div>
    </div>
</div>
