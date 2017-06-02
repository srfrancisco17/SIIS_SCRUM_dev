<?php

use yii\helpers\Html;
use kartik\sortable\Sortable;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel2 app\models\SprintRequerimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
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
        margin-top: 10px;
        margin-bottom: 10px;
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
        margin-top: 10px;
        margin-bottom: 10px;
        padding-bottom:30px;
    }

    .box-header>.fa, .box-header>.glyphicon, .box-header>.ion, .box-header .box-title {
        font-size: 13px;
    }
    td,th {
        padding: 10px;
    }
</style>  

<div class="kanban">
     
    <div class="row" style="background-color: #3c8dbc; color: #f0f0f0;">
        <div class="col-lg-3">
            <h4 class="text-center">Requerimiento</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center">Pendiente</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center">En Curso</h4>
        </div>
        <div class="col-lg-3" style="border-left: 1px solid;">
            <h4 class="text-center">Finalizado</h4>
        </div>
        <!--<hr>-->
        <br>
    </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'tareas-form',
                'enableAjaxValidation' => true,
                'enableClientScript' => true,
                'enableClientValidation' => true,
    ]);
    ?>
    <?php
        
        $this->registerJs("var form = $('#tareas-form');");
       
        
        for ($i = 0; $i < count($consulta); $i++) {
            $consulta1 = $consulta[$i]->getRequerimiento()->with('sprintRequerimientosTareas')->all();
        
        
        //echo $consulta[$i]->usuarioAsignado->color;
        
        //$requerimientos = \app\models\Requerimientos::find()->with('sprintRequerimientosTareas')->all();
        
        foreach ($consulta1 as $objRequerimientos) {
            $items1 = array(); 
            $items2 = array();
            $items3 = array();
            foreach ($objRequerimientos->sprintRequerimientosTareas as $objTareas){
                  
                switch ($objTareas->estado){
                    case 2: 
                     
                            $items1[$objTareas->tarea_id] = [
                            //'content' => $objTareas->tarea_descripcion,
                            'content' => '<div class="box box-default collapsed-box">
                                    <div class="box-header with-border">
                                      <h5 class="box-title">' . $objTareas->tarea_titulo . '</h5>
                                      <div class="box-tools pull-right">
                                        <span class="label label-default">'.$objTareas->tiempo_desarrollo.'</span>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                      </div><!-- /.box-tools -->
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                    ' . $objTareas->tarea_descripcion . '
                                    </div><!-- /.box-body -->
                                    </div><!-- /.box -->',
                            'options' => ['id' => $objTareas->tarea_id],
                                //'options' => ['data' => ['id'=>$$objTareas->tarea_id]],
                        ];  
                        
                        break;
                        //------------------------------------------------------------
                        case 3
                            : 
                     
                            $items2[$objTareas->tarea_id] = [
                            //'content' => $objTareas->tarea_descripcion,
                            'content' => '<div class="box box-default collapsed-box">
                                    <div class="box-header with-border">
                                      <h5 class="box-title">' . $objTareas->tarea_titulo . '</h5>
                                      <div class="box-tools pull-right">
                                        <span class="label label-default">40</span>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                      </div><!-- /.box-tools -->
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                    ' . $objTareas->tarea_descripcion . '
                                    </div><!-- /.box-body -->
                                    </div><!-- /.box -->',
                            'options' => ['id' => $objTareas->tarea_id],
                                //'options' => ['data' => ['id'=>$$objTareas->tarea_id]],
                        ];  
                        
                        break;
                        //----------------------------------------------------------------
                        case 4: 
                     
                            $items3[$objTareas->tarea_id] = [
                            //'content' => $objTareas->tarea_descripcion,
                            'content' => '<div class="box box-default collapsed-box">
                                    <div class="box-header with-border">
                                      <h5 class="box-title">' . $objTareas->tarea_titulo . '</h5>
                                      <div class="box-tools pull-right">
                                        <span class="label label-default">40</span>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                      </div><!-- /.box-tools -->
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                    ' . $objTareas->tarea_descripcion . '
                                    </div><!-- /.box-body -->
                                    </div><!-- /.box -->',
                            'options' => ['id' => $objTareas->tarea_id],
                                //'options' => ['data' => ['id'=>$$objTareas->tarea_id]],
                        ];  
                        
                        break;
                }
                
            }
        ?>
        
         <!-- CAMBIAR EL COLOR A LAS ROWS -->
         <?php
            if ($i%2==0){
                  echo '<div class="row" style="background-color: #E2E4EA;">';
                  //par
            }else{
                  echo '<div class="row" style="background-color: #D2D6DE;">';
                  //impar
            }
         ?>
         <!-- ------------------------------------- -->
        <!--<div class="row">-->
            <div class="col-lg-3">
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=  $objRequerimientos->requerimiento_titulo ?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?=  strip_tags($objRequerimientos->requerimiento_descripcion) ?>
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
                            form.action = "index.php?r=sprint-usuarios/respuesta&id="+var1+"&estado="+2,
                            form.serialize()
                        ).done(function(result) {
                            form.parent().html(result.message);
                            //$.pjax.reload({container:"#tareas-form"}); 

                        });
                                
                            }',
                        //'sortupdate' => 'function(e,b) { console.log(b.item[0].getAttribute("data-id")); }', 
                        ],
                        //'options' => ['class' => 'color:red'],
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
                            form.action = "index.php?r=sprint-usuarios/respuesta&id="+var1+"&estado="+3,
                            form.serialize()
                        ).done(function(result) {
                            form.parent().html(result.message);
                            //$.pjax.reload({container:"#tareas-form"}); 

                        });
                                
                            }',
                        //'sortupdate' => 'function(e,b) { console.log(b.item[0].getAttribute("data-id")); }', 
                        ],
                        //'options' => ['class' => 'color:red'],
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
                            form.action = "index.php?r=sprint-usuarios/respuesta&id="+var1+"&estado="+4,
                            form.serialize()
                        ).done(function(result) {
                            form.parent().html(result.message);
                            //$.pjax.reload({container:"#tareas-form"}); 

                        });
                                
                            }',
                        //'sortupdate' => 'function(e,b) { console.log(b.item[0].getAttribute("data-id")); }', 
                        ],
                        //'options' => ['class' => 'color:red'],
                    ]);
                    echo '<div class="clearfix"></div>';
                ?> 
            </div>
        </div>
    <?php

        }
        
        }
    ?>    
    <?php ActiveForm::end() ?>
   
<?php
//$items2_options = ['style' => ['background-color' => 'blue', 'color'=>'white', 'width' => '100px', 'height' => '100px']];
$options2 = ['style' => ['background-color' => 'blue', 'min-height' => '100px', 'padding-bottom' => '30px']];
//$options3 = ['style' => ['background-color' => 'red']];
// Two connected Sortable lists with custom styles.

?>
</div>
