<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

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
            new Date('2017-04-10'), 
            new Date('2017-04-30'), 
            null,
            <?= round($duracion_requerimiento) ?>, 
            null
        ],
         
         <?php
            }
         ?>
      ]);

      var options = {
        height: 250,
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
    print_r($sprint_requerimientos[0]->sprint->fecha_desde);
    echo '</pre>';
?>
