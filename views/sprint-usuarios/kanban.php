<?php

use yii\helpers\Html;
use kartik\sortable\Sortable;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = 'Tareas';
$this->params['breadcrumbs'][] = ['label' => 'Mis Sprints', 'url' => ['sprint-usuarios/index']];
$this->params['breadcrumbs'][] = $this->title;
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
        cursor: normal;
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
    
    .box-footer {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        border-top: 0px solid;
        padding: 5px;
        background-color: #fff;
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
                    $html .=        $sprint_usuarios->sprint->sprint_alias;
                    $html .= "  </b>";
                    $html .= "</a>";
                    $html .= "[".$sprint_usuarios->sprint->fecha_desde." / ".$sprint_usuarios->sprint->fecha_hasta."]";
                   
                    $html .= "<br/>";
                    $html .= "HORAS DE SOPORTE: ".$sprint_usuarios->horas_establecidas_soporte;
                    
                    echo $html;
                    
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="kanban">
    <div class="row" style="background-color: #605ca8; color: #f5f5f5;">
        <div class="col-lg-3">
            <h4 class="text-center" style="font-family: Century Gothic;">Requerimiento</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center" style="font-family: Century Gothic;">En Espera   <!--<span class="label label-default">10</span>--></h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center" style="font-family: Century Gothic;">En Progreso  <!--<span class="label label-primary">20</span>--></h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center" style="font-family: Century Gothic;">Terminado  <!--<span class="label label-primary">30</span>--></h4>
        </div>
        <br>
    </div>
    <?php Pjax::begin(['id' => 'grid_tareas']); ?>
    <?php
        $form = ActiveForm::begin([
                    'id' => 'grid-requerimientos_tareas',
                    'enableAjaxValidation' => true,
                    'enableClientScript' => true,
                    'enableClientValidation' => true,
        ]);
    ?>
    <?php
    
        $buttons_pull_right = '';
        $this->registerJs("var form = $('#tareas-form1');");
        
        $usuario_color = '#656565';
        
        if (!empty(Yii::$app->user->identity->color)){
            $usuario_color = Yii::$app->user->identity->color;
        }

        for ($i = 0; $i < count($consulta); $i++) {
            $consulta1 = $consulta[$i]->getRequerimiento()->with('sprintRequerimientosTareas')->all();
        
          
        foreach ($consulta1 as $objRequerimientos) {
            $items1 = array(); 
            $items2 = array();
            $items3 = array();
            
            foreach ($objRequerimientos->sprintRequerimientosTareas as $objTareas){
            
            if ($objTareas->sprint_id == $sprint_id){
                
                if($objTareas->estado == 2){
                        
                    if ($objRequerimientos->sw_soporte == 1){
                        
                        $buttons_pull_right = '
                            <div class="box-tools pull-right">
                                <button id="activity-index-link" type="button" class="btn btn-box-tool botones" data-toggle="modal" data-target="#modal" data-url='.Url::to(['requerimientos/update-requerimientos-tareas', 'tarea_id' => $objTareas->tarea_id , 'sprint_id' => $objTareas->sprint_id]).' data-pjax="0" data-opcion="modal1-update"><i class="fa fa-pencil"></i></button>
                                '.
                                Html::a('<span class="fa fa-trash" style="color: #d9d9d9;"></span>', ['requerimientos-tareas/delete', 'sprint_id' => $objTareas->sprint_id, 'tarea_id' => $objTareas->tarea_id], [
                                    'title' => 'Eliminar',
                                    'onclick' => "
                                     
                                        if (confirm('Esta seguro de eliminar este registro?')) {
                                            $.ajax('".Url::to(['requerimientos/delete-requerimientos-tareas', 'tarea_id' => $objTareas->tarea_id, 'sprint_id' =>$objTareas->sprint_id])."', {
                                                type: 'POST'
                                            }).done(function(data) {
                                                $.pjax.reload({container: '#grid_tareas'});
                                            });
                                        }
                                        return false;
                                    ",
                                ])
                                .'
                            </div>';          
                    }
                    
                    $items1[$objTareas->tarea_id] = [
                        'content' => '<div data-toggle="tooltip" data-placement="right" title="'.$objTareas->tarea_id.'" class="box box-default collapsed-box" style="background-color: '.$usuario_color.';">
                                <div class="box-header with-border">
                                  <h5 class="box-title">' . $objTareas->tarea->tarea_titulo . '</h5>
                                  <div class="box-tools pull-right">
                                    <span class="label label-default">'.$objTareas->tarea->horas_desarrollo.'</span>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                   </div><!-- /.box-tools -->
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                ' .str_replace("\n", "<br>", $objTareas->tarea->tarea_descripcion). '
                                </div><!-- /.box-body -->
                                <div class="box-footer" style="background-color:'.$usuario_color.'">
                                '.$buttons_pull_right.'
                                </div>
                                </div><!-- /.box -->',
                        'options' => ['id' => $objTareas->tarea_id],
                    ];
  
                }else if($objTareas->estado == 3){
                    if ($objRequerimientos->sw_soporte == 1){
                        
                        $buttons_pull_right = '
                            <div class="box-tools pull-right">
                                <button id="activity-index-link" type="button" class="btn btn-box-tool botones" data-toggle="modal" data-target="#modal" data-url='.Url::to(['requerimientos/update-requerimientos-tareas', 'tarea_id' => $objTareas->tarea_id , 'sprint_id' => $objTareas->sprint_id]).' data-pjax="0" data-opcion="modal1-update"><i class="fa fa-pencil"></i></button>
                                '.
                                Html::a('<span class="fa fa-trash" style="color: #d9d9d9;"></span>', ['requerimientos-tareas/delete', 'sprint_id' => $objTareas->sprint_id, 'tarea_id' => $objTareas->tarea_id], [
                                    'title' => 'Eliminar',
                                    'onclick' => "
                                     
                                        if (confirm('Esta seguro de eliminar este registro?')) {
                                            $.ajax('".Url::to(['requerimientos/delete-requerimientos-tareas', 'tarea_id' => $objTareas->tarea_id, 'sprint_id' =>$objTareas->sprint_id])."', {
                                                type: 'POST'
                                            }).done(function(data) {
                                                $.pjax.reload({container: '#grid_tareas'});
                                            });
                                        }
                                        return false;
                                    ",
                                ])
                                .'
                            </div>';          
                    }
                    $items2[$objTareas->tarea_id] = [
                        'content' => '<div data-toggle="tooltip" data-placement="right" title="'.$objTareas->tarea_id.'" class="box box-default collapsed-box" style="background-color: '.$usuario_color.';">
                                <div class="box-header with-border">
                                  <h5 class="box-title">' . $objTareas->tarea->tarea_titulo . '</h5>
                                  <div class="box-tools pull-right">
                                    <span class="label label-default">'.$objTareas->tarea->horas_desarrollo.'</span>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                   </div><!-- /.box-tools -->
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                ' .str_replace("\n", "<br>", $objTareas->tarea->tarea_descripcion). '
                                </div><!-- /.box-body -->
                                <div class="box-footer" style="background-color:'.$usuario_color.'">
                                    '.$buttons_pull_right.'
                                </div>
                                </div><!-- /.box -->',
                        'options' => ['id' => $objTareas->tarea_id],
                    ];                      
                    
                }else if ($objTareas->estado == 4){
                    
                    if ($objRequerimientos->sw_soporte == 1){
                        
                        $buttons_pull_right = '
                            <div class="box-tools pull-right">
                                <button id="activity-index-link" type="button" class="btn btn-box-tool botones" data-toggle="modal" data-target="#modal" data-url='.Url::to(['requerimientos/update-requerimientos-tareas', 'tarea_id' => $objTareas->tarea_id , 'sprint_id' => $objTareas->sprint_id]).' data-pjax="0" data-opcion="modal1-update"><i class="fa fa-pencil"></i></button>
                                '.
                                Html::a('<span class="fa fa-trash" style="color: #d9d9d9;"></span>', ['requerimientos-tareas/delete', 'sprint_id' => $objTareas->sprint_id, 'tarea_id' => $objTareas->tarea_id], [
                                    'title' => 'Eliminar',
                                    'onclick' => "
                                     
                                        if (confirm('Esta seguro de eliminar este registro?')) {
                                            $.ajax('".Url::to(['requerimientos/delete-requerimientos-tareas', 'tarea_id' => $objTareas->tarea_id, 'sprint_id' =>$objTareas->sprint_id])."', {
                                                type: 'POST'
                                            }).done(function(data) {
                                                $.pjax.reload({container: '#grid_tareas'});
                                            });
                                        }
                                        return false;
                                    ",
                                ])
                                .'
                            </div>';          
                    }
                    $items3[$objTareas->tarea_id] = [
                        'content' => '<div data-toggle="tooltip" data-placement="left" title="'.$objTareas->tarea_id.'" class="box box-default collapsed-box" style="background-color: '.$usuario_color.';">
                                <div class="box-header with-border">
                                  <h5 class="box-title">' . $objTareas->tarea->tarea_titulo . '</h5>
                                  <div class="box-tools pull-right">
                                    <span class="label label-default">'.$objTareas->tarea->horas_desarrollo.'</span>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                  </div><!-- /.box-tools -->
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                ' .str_replace("\n", "<br>", $objTareas->tarea->tarea_descripcion). '
                                </div><!-- /.box-body -->
                                <div class="box-footer" style="background-color:'.$usuario_color.'">
                                    <div class="box-tools pull-right">
                                        '.$buttons_pull_right.'        
                                    </div>
                                </div><!-- /.box -->',
                        'options' => ['id' => $objTareas->tarea_id],
                    ];   
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
                <div class="box box-default collapsed-box" style="background-color: <?= $usuario_color?>;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= "[".$objRequerimientos->requerimiento_id."] ".$objRequerimientos->requerimiento_titulo ?></h3>
                        <div class="box-tools pull-right">
                            <span class="label label-default"><?= $consulta[$i]['tiempo_desarrollo'] ?></span>
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?=  strip_tags($objRequerimientos->requerimiento_descripcion) ?>
                    </div>
                    <div class="box-footer" style="background-color: <?= $usuario_color?>; text-align: right;">
                       
                        <?php
                        if ($objRequerimientos->sw_soporte == 1){
                            
                            echo Html::a('<span class="glyphicon glyphicon-list-alt" style="color: #d9d9d9;"></span>', '#', [
                                'class' => 'botones',
                                'data-toggle' => 'modal',
                                'data-target' => '#modal',
                                'data-url' => Url::to(['requerimientos/create-requerimientos-tareas', 'sprint_id' => $sprint_id ,'requerimiento_id' => $objRequerimientos->requerimiento_id]),
                                'data-pjax' => '0',
                                'data-opcion' => 'modal1-create'
                            ]);
                   
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 columna-sortable">

                <?php
                    echo Sortable::widget([
                        'connected' => $objRequerimientos->requerimiento_id.''  ,
                        'type' => 'list',
                        'items'=> $items1,

                        'pluginEvents' => [
                            'sortupdate' => 'function(e,b) { 
                                console.log(b.item[0].id); 
                             
                        var1 = b.item[0].id;
                           
                        $.post(
                            form.action = "index.php?r=sprint-usuarios/respuesta&tarea_id="+var1+"&estado="+2+"&sprint_id="+"'.$sprint_id.'"+"&requerimiento_id="+"'.$objRequerimientos->requerimiento_id.'",
                            form.serialize()
                        ).done(function(result) {
                            form.parent().html(result.message);
                            //$.pjax.reload({container:"#tareas-form1"}); 

                        });
                                
                            }',
                        ],
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

                        'pluginEvents' => [
                            'sortupdate' => 'function(e,b) { 
                                console.log(b.item[0].id); 
                             
                        var1 = b.item[0].id;
                          
                        $.post(
                            form.action = "index.php?r=sprint-usuarios/respuesta&tarea_id="+var1+"&estado="+3+"&sprint_id="+"'.$sprint_id.'"+"&requerimiento_id="+"'.$objRequerimientos->requerimiento_id.'",
                            form.serialize()
                        ).done(function(result) {
                            form.parent().html(result.message);
                            //$.pjax.reload({container:"#tareas-form1"}); 

                        });
                                
                            }',
                        ],
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

                        'pluginEvents' => [
                            'sortupdate' => 'function(e,b) { 
                                console.log(b.item[0].id); 
                             
                        var1 = b.item[0].id;
                      
                        $.post(
                            form.action = "index.php?r=sprint-usuarios/respuesta&tarea_id="+var1+"&estado="+4+"&sprint_id="+"'.$sprint_id.'"+"&requerimiento_id="+"'.$objRequerimientos->requerimiento_id.'",
                           
                            
                            form.serialize()
                        ).done(function(result) {
                            form.parent().html(result.message);
                            //$.pjax.reload({container:"#tareas-form1"}); 

                        });
                                
                            }', 
                        ],
                    ]);
                    echo '<div class="clearfix"></div>';
                ?> 
            </div>
        </div>



    <?php

        }
        
        }
    
    ActiveForm::end();
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