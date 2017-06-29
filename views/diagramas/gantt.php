<?php
/* @var $this yii\web\View */
//use yii\helpers\Html;
//use kartik\helpers\Html;

$this->title = 'Diagrama Gantt';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/loader.js', ['position' => $this::POS_HEAD]);
?>
<?php
    
//        echo '<pre>';
//        print_r($consulta[0]->sprint->fecha_desde);
//        echo '</pre>';
//        exit;
?>
<script type="text/javascript">
    google.charts.load('current', {'packages':['gantt']});
    
    <?php
    
        $contador = 1;
        
        $fecha_sprint_inicial = $consulta[0]->sprint->fecha_desde;
        

            foreach ($consulta as $obj_usuarios){
                
            
    ?>   
            function drawChart<?=$contador?>(){
                
                
                var data<?=$contador?> = new google.visualization.DataTable();
                data<?=$contador?>.addColumn('string', 'Task ID');
                data<?=$contador?>.addColumn('string', 'Task Name');
                data<?=$contador?>.addColumn('string', 'Resource');
                data<?=$contador?>.addColumn('date', 'Start Date');
                data<?=$contador?>.addColumn('date', 'End Date');
                data<?=$contador?>.addColumn('number', 'Duration');
                data<?=$contador?>.addColumn('number', 'Percent Complete');
                data<?=$contador?>.addColumn('string', 'Dependencies');
                
                /*
                 * foreach de usuarios
                 */
                
                data<?=$contador?>.addRows([
                <?php
                
                
                $tamaño_chart = 0;
                $var2 = $obj_usuarios->sprintRequerimientos;
                    
                    foreach ($var2 as $sprint_requerimientos) {
                       
                        
                        $tamaño_chart++;
                        
                        if ($sprint_requerimientos->prioridad == 1){

                            $inicio = $fecha_sprint_inicial;

                            $dia_requerimientos = ceil($sprint_requerimientos->tiempo_desarrollo/8);
                            $fecha = date($inicio);

                            $suma_fecha = strtotime('+'.$dia_requerimientos.' day', strtotime($fecha));

                            $fin_fecha_requerimiento =  date('Y-m-d', $suma_fecha);
                        }
                        
                        else if ($sprint_requerimientos !== end($var2)) {

                            $inicio = $fin_fecha_requerimiento;

                            $dia_requerimientos = ceil($sprint_requerimientos->tiempo_desarrollo/8);
                            $fecha = date($inicio);

                            $suma_fecha = strtotime('+'.$dia_requerimientos.' day', strtotime($fecha));

                            $fin_fecha_requerimiento =  date('Y-m-d', $suma_fecha);

                        }
                        
                        if ($sprint_requerimientos === end($var2)) {
                            
                            $inicio = $fin_fecha_requerimiento;

                            $dia_requerimientos = ceil($sprint_requerimientos->tiempo_desarrollo/8);
                            $fecha = date($inicio);

                            $suma_fecha = strtotime('+'.$dia_requerimientos.' day', strtotime($fecha));

                            $fin_fecha_requerimiento =  $consulta[0]->sprint->fecha_hasta;

                            
                        }
                    
                     $titulo = substr($sprint_requerimientos->requerimiento->requerimiento_titulo, 0, 40);   
                ?>   
                            ['<?= $sprint_requerimientos->requerimiento->requerimiento_id ?>', '<?= $titulo ?>', '<?=$sprint_requerimientos->prioridad?>',
                            new Date('<?= $inicio ?>'), new Date('<?= $fin_fecha_requerimiento ?>'), null, 100, null],     
                <?php 
                        }
                ?>
                      
                    
                ]);
                
                
                var options = {
                        height: <?= 100 + ($tamaño_chart*25)?>,
                    gantt: {
                        trackHeight: 30
                    }
                };

                var chart<?=$contador?> = new google.visualization.Gantt(document.getElementById('chart_div_<?=$contador?>'));

                chart<?=$contador?>.draw(data<?=$contador?>, options);

            }
            
    <?php  
            $suma[] = $contador;
            $contador++;
            
        }
        
        foreach ($suma as $value){
    ?>
        google.charts.setOnLoadCallback(drawChart<?=$value?>);
    <?php                  
        }
    ?>
        
   
 
 
 </script>
 <?php
         foreach ($suma as $value){
    ?>
 
    <div class="box box-default">
       <div class="box-header with-border">
         <h3 class="box-title"><?= $consulta[$value-1]->usuario->nombreCompleto ?></h3>
         <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
           <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
         </div>
       </div>
       <div class="box-body">      
           <div id="chart_div_<?=$value?>"></div>
       </div>
       <!--
       <div class="box-footer">
           <p>Footer</p>
       </div>
       -->
   </div>
  
     <br>
    <?php                  
        }
    ?>

