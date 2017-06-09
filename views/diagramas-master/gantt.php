<?php
/* @var $this yii\web\View */
//use yii\helpers\Html;
//use kartik\helpers\Html;

$this->title = 'Gantt';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/loader.js', ['position' => $this::POS_HEAD]);
?>
 <script type="text/javascript">
    google.charts.load('current', {'packages':['gantt']});
    google.charts.setOnLoadCallback(drawChart);
    
    function daysToMilliseconds(days) {
      return days * 24 * 60 * 60 * 1000;
    }

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Task ID');
      data.addColumn('string', 'Task Name');
      data.addColumn('string', 'Resource');
      data.addColumn('date', 'Start Date');
      data.addColumn('date', 'End Date');
      data.addColumn('number', 'Duration');
      data.addColumn('number', 'Percent Complete');
      data.addColumn('string', 'Dependencies');

      data.addRows([
        <?php
        
        $fecha_sprint_inicial = $sprint_requerimientos[0]->sprint->fecha_desde;
        //$fin_fecha_requerimiento = '2017-05-30';
            foreach ($sprint_requerimientos as $value) { 
                
                
                
                $tareas_tiempo = 0;
                $duracion_requerimiento = 0;
                if (!empty($value->tiempo_desarrollo)){
                
                    foreach ($value->requerimiento->sprintRequerimientosTareas as $tareas) {
                        if ($tareas->estado == 4){
                            $tareas_tiempo+=$tareas->tiempo_desarrollo;
                        }
                    }

                    $duracion_requerimiento = (100*$tareas_tiempo)/$value->tiempo_desarrollo;
                }
        ?>
        [
            '<?= $value->requerimiento->requerimiento_id;?>', 
            '<?= $value->requerimiento->requerimiento_titulo;?>', 
            'usuario'+'<?= $value->usuario_asignado;?>',
        <?php
        
            
        
            if ($value->prioridad == 1){
                
                $inicio = $fecha_sprint_inicial;
                
                $dia_requerimientos = round($value->tiempo_desarrollo/8);
                $fecha = date($inicio);
                
                $suma_fecha = strtotime('+'.$dia_requerimientos.' day', strtotime($fecha));
                
                $fin_fecha_requerimiento =  date('Y-m-d', $suma_fecha);
            }
            else{
                $inicio = $fin_fecha_requerimiento;
                
                $dia_requerimientos = round($value->tiempo_desarrollo/8);
                $fecha = date($inicio);
                
                $suma_fecha = strtotime('+'.$dia_requerimientos.' day', strtotime($fecha));
                
                $fin_fecha_requerimiento =  date('Y-m-d', $suma_fecha);
                
            }
        ?>
            new Date('<?= $inicio ?>'), 
            new Date('<?= $fin_fecha_requerimiento ?>'), 
            null,
            <?= round($duracion_requerimiento) ?>, 
            null
        ],
         
         <?php
            }
         ?>
      ]);

      var options = {
        height: 350,
        gantt: {
          trackHeight: 30
        }
      };

      var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
  </script>
  <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Diagrama De Gantt</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            
            <div id="chart_div"></div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <p>Footer</p>
        </div>
</div>
<?php   

    echo '<pre>';
    var_dump($sprint_requerimientos[0]);
    echo '</pre>';
?>
