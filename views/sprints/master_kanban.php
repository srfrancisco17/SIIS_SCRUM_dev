<?php

use yii\helpers\Html;
use kartik\sortable\Sortable;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel2 app\models\SprintRequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Sprint Kanban';
$this->params['breadcrumbs'][] = ['label' => 'Sprints', 'url' => ['sprint/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('[data-toggle=\"tooltip\"]').tooltip();");


?>
<style>
    .sortable.grid li {
        float: left;
        min-width: 100px;
        min-height: 100px;
        text-align: center;
    }
    hr { 
        display: block;
        margin-top: 0.5em;
        margin-bottom: 0.5em;
        margin-left: auto;
        margin-right: auto;
        border-style: inset;
        border-width: 1px;
        box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
    }
    .sortable li {
        border: 1px solid #ddd;
        list-style: none outside none;
        margin: 0px;
        padding: 0px;
        cursor: default;
    }

    .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        border-top: 0px;
        margin-top: 0px;
        margin-bottom: 5px;
        width: 100%;
    }
    
    .sortable {
        -moz-user-select: none;
        padding: 0;
        border-radius: 4px;
        border: 0px;
    }

    .sortable li.sortable-placeholder {
        /*background: none repeat scroll 0 0 rgba(0, 0, 0, 0);*/
        background-color: #f0f0f0;
        border: 1px dashed #CCCCCC;
        padding: 16px;
        height: 60px;
    }

    div.columna-sortable > ul{
        background-color: none;
        min-height:100px; 
        margin-top: 0px;
        margin-bottom: 10px;
        padding-bottom:30px;
    }

    .box-header>.fa, .box-header>.glyphicon, .box-header>.ion, .box-header .box-title {
        font-size: 13px;
        font-weight: 600;
        color: #671f31;
    }
    td,th {
        padding: 10px;
    }
    .btn-box-tool {
       color: #d9d9d9; 
    }
    
</style> 
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="box-header with-border">
                <p class="text-center" style="margin: 0 0 0px;">                  
                    <?php

                    $html = "";
                    $html .= "<a href='javascript:void(0)' data-toggle='tooltip' data-placement='right' title='' >";
                    $html .= "  <b>";
                    $html .=        $consulta[0]->sprint->sprint_alias;
                    $html .= "  </b>";
                    $html .= "</a>";    
                    $html .= "[".$consulta[0]->sprint->fecha_desde." / ".$consulta[0]->sprint->fecha_hasta."]";
                    
                    echo $html;
                    
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="kanban">
<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid collapsed-box">
            <div class="box-header with-border">
                <p class="text-center" style="margin: 0 0 0px;"><b>DESARROLLADORES</b></p>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                </div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>DESARROLLADOR</th>
                            <th>COLOR</th>
                            <th>HORAS DE SOPORTE</th>
                            <th>TOTAL DE HORAS</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $contador = 1;
                            foreach ($consulta_usuarios as $value){  
                                
                                $suma_total = 0;
                                
                                foreach ($value->sprintRequerimientos as $sprint_requerimientos ){
                                    $suma_total = $suma_total+ $sprint_requerimientos->tiempo_desarrollo;
                                    
                                }
                        ?>
                            <tr>
                                <td><?= $contador ?></td>
                                <td> <?= Html::a($value->usuario->nombreCompleto, '#'.$value->usuario->usuario_id) ?> </td>
                                <td style ='background-color: <?= $value->usuario->color ?>'><?= $value->usuario->color ?></td>
                                <td><?= $value->horas_establecidas_soporte ?></td>
                                <td><?= $suma_total ?></td>
                            </tr>
                            
                        <?php
                            $contador++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
    <br>
    <div class="row" style="background-color: #5A6E83; color: #f0f0f0;">
        <div class="col-lg-3">
            <h4 class="text-center" style="font-family: Century Gothic;">REQUERIMIENTO</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center" style="font-family: Century Gothic;">PENDIENTE</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center" style="font-family: Century Gothic;">EN CURSO</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center" style="font-family: Century Gothic;">FINALIZADO</h4>
        </div>
        <br>
    </div>
    <?php
        
        Pjax::begin(['id' => 'grid_tareas']);

        for ($i = 0; $i < count($consulta); $i++) {
            $consulta1 = $consulta[$i]->getRequerimiento()->with('sprintRequerimientosTareas')->all();

            $usuario_color = empty($consulta[$i]->usuarioAsignado->color) ? '#656565' : $consulta[$i]->usuarioAsignado->color;
            $usuario_id = empty($consulta[$i]->usuarioAsignado->usuario_id) ? '' : $consulta[$i]->usuarioAsignado->usuario_id;
        
        foreach ($consulta1 as $objRequerimientos)  {
            $items1 = array(); 
            $items2 = array();
            $items3 = array();
            
            
            foreach ($objRequerimientos->sprintRequerimientosTareas as $objTareas){
                
                if ($objTareas->sprint_id == $sprint_id){
                    switch ($objTareas->estado){
                        case 2: 

                                $items1[$objTareas->tarea_id] = [
                                //'content' => $objTareas->tarea_descripcion,
                                'content' => '<div data-toggle="tooltip" data-placement="right" title="'.$objTareas->tarea_id.'" class="box box_faof collapsed-box" style="background-color: '.$usuario_color.';">
                                        <div class="box-header">
                                          <h5 class="box-title">' . $objTareas->tarea->tarea_titulo . '</h5>
                                          <div class="box-tools pull-right">
                                            <span class="label label-default">'.$objTareas->tarea->horas_desarrollo.'</span>
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                          </div><!-- /.box-tools -->
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                        ' . str_replace("\n", "<br>", $objTareas->tarea->tarea_descripcion) . '
                                        </div><!-- /.box-body -->
                                        </div><!-- /.box -->',
                                'options' => ['id' => $objTareas->tarea_id],
                            ];  

                            break;
                            //------------------------------------------------------------
                            case 3: 

                                $items2[$objTareas->tarea_id] = [
                                'content' => '<div data-toggle="tooltip" data-placement="right" title="'.$objTareas->tarea_id.'" class="box box_faof collapsed-box" style="background-color: '.$usuario_color.';">
                                        <div class="box-header">
                                          <h5 class="box-title">' . $objTareas->tarea->tarea_titulo . '</h5>
                                          <div class="box-tools pull-right">
                                            <span class="label label-default">'.$objTareas->tarea->horas_desarrollo.'</span>
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                          </div><!-- /.box-tools -->
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                        ' . str_replace("\n", "<br>", $objTareas->tarea->tarea_descripcion) . '
                                        </div><!-- /.box-body -->
                                        </div><!-- /.box -->',
                                'options' => ['id' => $objTareas->tarea_id],
                            ];  

                            break;
                            //----------------------------------------------------------------
                            case 4: 

                                $items3[$objTareas->tarea_id] = [
                                'content' => '<div data-toggle="tooltip" data-placement="left" title="'.$objTareas->tarea_id.'" class="box box_faof collapsed-box" style="background-color: '.$usuario_color.';">
                                        <div class="box-header">
                                          <h5 class="box-title">' . $objTareas->tarea->tarea_titulo . '</h5>
                                          <div class="box-tools pull-right">
                                            <span class="label label-default">'.$objTareas->tarea->horas_desarrollo.'</span>
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                          </div><!-- /.box-tools -->
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                        ' . str_replace("\n", "<br>", $objTareas->tarea->tarea_descripcion) . '
                                        </div><!-- /.box-body -->
                                        </div><!-- /.box -->',
                                'options' => ['id' => $objTareas->tarea_id],
                            ];  
                            break;
                    }  
                    
                }
            }
        ?>
         <!-- CAMBIAR EL COLOR A LAS ROWS -->
         <?php
            if ($i%2==0){
                  echo '<div class="row" style="background-color: #E2E4EA;">';
                  //par
                  echo '<br>';
            }else{
                  echo '<div class="row" style="background-color: #D2D6DE;">';
                  //impar
                  echo '<br>';
            }
         ?>
            <div class="col-lg-3">
                
                <div id="<?= $usuario_id ?>" class="box box_faof collapsed-box" style="background-color: <?= $usuario_color ?>;">
                    <div class="box-header">
                        <h3 class="box-title"><?= "[".$objRequerimientos->requerimiento_id."] ".$objRequerimientos->requerimiento_titulo ?></h3>
                        <div class="box-tools pull-right">
                            <span class="label label-default"><?= $consulta[$i]['tiempo_desarrollo'] ?></span>
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?=  strip_tags($objRequerimientos->requerimiento_descripcion) ?>
                    </div>
                    <?php
                    if ($objRequerimientos->sw_soporte == 1){

                        echo '<div class="box-footer" style="background-color: '.$usuario_color.'; text-align: right;">';

                        echo Html::a('<span class="glyphicon glyphicon-list-alt" style="color: #d9d9d9;"></span>', '#', [
                            'class' => 'botones',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'data-url' => Url::to(['requerimientos/create-requerimientos-tareas', 'sprint_id' => $sprint_id ,'requerimiento_id' => $objRequerimientos->requerimiento_id]),
                            'data-pjax' => '0',
                            'data-opcion' => 'modal1-create'
                        ]);

                        echo '</div>';

                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-3 columna-sortable">
 
                <?php
                    echo Sortable::widget([
                        'connected' => $objRequerimientos->requerimiento_id.''  ,
                        'type' => 'list',
                        'items'=> $items1,
                        'disabled'=>true,
                    ]);
                    echo '<div class="clearfix"></div>';
                ?>                
            </div>
            <div class="col-lg-3 columna-sortable">
 
                <?php
                    echo Sortable::widget([
                        'connected' => $objRequerimientos->requerimiento_id.'',
                        'type' => 'list',
                        'items'=> $items2,
                        'disabled'=>true,
                    ]);
                    echo '<div class="clearfix"></div>';
                ?>  
            </div>
            <div class="col-lg-3 columna-sortable">
                <?php
                    echo Sortable::widget([
                        'connected' => $objRequerimientos->requerimiento_id.'',
                        'type' => 'list',
                        'items'=> $items3,
                        'disabled'=>true,
                    ]);
                    echo '<div class="clearfix"></div>';
                ?> 
            </div>
        </div>
    <?php

        }
        }

		$this->registerJs("
			$('.box_faof').boxWidget({
			  animationSpeed: 500,
			  collapseIcon: 'fa-minus',
			  expandIcon: 'fa-plus',
			  removeIcon: 'fa-times'
			});
			$('.box_faof').boxWidget('collapse');
		");
		
        Pjax::end();
        
        /*MODAL*/
        $this->registerJs("
            $(document).on('click', '.botones', (function() {   
                var texto_titulo = '';
                var propiedades_modal = $(this).data('opcion').split('-');

                if (propiedades_modal[0] === 'modal1'){

                    if (propiedades_modal[1] === 'create'){
                        texto_titulo = 'CREAR TAREA';
                        $('#modal').find('.modal-header').css('background-color','#008C4D');
                    }else if (propiedades_modal[1] === 'update'){
                        $('#modal').find('.modal-header').css('background-color','#367EA8');
                        texto_titulo = 'ACTUALIZAR TAREA';
                    }
                }

                $('#titulo_modal').text(texto_titulo);

                $.get(
                    $(this).data('url'),
                    function (data) {

                        $('.modal-body').html(data);
                        $('#modal').modal();
                    }
                );
            }));
        ");    

        Modal::begin([
            'id' => 'modal',
            'header' => '<h5 style="font-weight: bold; color:white;" id="titulo_modal" class="modal-title"></h5>',
        ]);
        echo "<div class='well'></div>";
        Modal::end();
        
        
    ?>    