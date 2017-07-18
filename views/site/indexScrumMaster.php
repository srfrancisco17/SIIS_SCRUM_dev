<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\assets\HighchartsAssets;


HighchartsAssets::register($this);
$this->title = 'Dashboard Scrum Master';
$this->params['breadcrumbs'][] = $this->title;

$last_position = end($array_sprints);

//$this->registerJsFile('@web/js/loader.js', ['position' => $this::POS_HEAD]);

?>
<?php Pjax::begin(); ?>
<div class="row">
    <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
                <!--<h3 class="box-title"></h3>-->
                    <div class="row">
                        <?= Html::beginForm(['site/index-scrum-master'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
                        
                        <div class="col-lg-2">
                            <?= Select2::widget([
                                'name' => 'sprint_id',
                                'size' => Select2::MEDIUM,
                                //'value' => Yii::$app->request->post('sprint_id', $array_sprints[sizeof($array_sprints)-1]['sprint_id']), // value to initialize
                                'value' => Yii::$app->request->post('sprint_id', $last_position['sprint_id']),
                                'data' => ArrayHelper::map($array_sprints, 'sprint_id', 'sprint_alias'),
                            ])?>
                        </div>
                        
                        <div class="col-lg-3">
                            <?= Select2::widget([
                                'name' => 'list',
                                'size' => Select2::MEDIUM,
                                'value' => Yii::$app->request->post('list'),
                                'options' => ['placeholder'=>'Todos Los Usuarios'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'data' =>  ArrayHelper::map($array_sprints_usuarios, 'usuario_id', 'usuario.nombreCompleto'),
                            ])?>

                        </div>
                        <div class="col-lg-2">
                                <?= Html::submitButton('Cargar', ['class' => 'btn btn-md btn-primary', 'name' => 'hash-button']) ?>
                        </div>
                        <?= Html::endForm() ?>
                    </div>                     
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <!--<li><a href="#">Action</a></li>-->
                    <li><?= Html::a('Ir al sprint', ['sprint-requerimientos/index', 'sprint_id' => 1]) ?></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <br>
                    <h5 class="description-header"><?= $consulta_total_requerimientos ?></h5>
                    <span class="description-text">Total Requerimientos</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <?= $html_span_requerimientos ?>
                    <h5 class="description-header"><?= $consulta_total_requerimientos_terminados ?></h5>
                    <span class="description-text">Total Terminados</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <br>
                    <h5 class="description-header"><?= $consulta_total_tareas ?></h5>
                    <span class="description-text">Total Tareas</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block">
                    <?= $html_span_tareas ?>
                    <h5 class="description-header"><?= $consulta_total_tareas_terminadas ?></h5>
                    <span class="description-text">Total Tareas Terminadas</span>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafico de barras</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                
              <div id="container2" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
              
            </div>
            <!-- /.box-body -->
          </div>
    </div>
</div>

<!-- Diagrama de Barras -->
<!--
<div class="row">
    <div class="col-lg-4">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafico de barras</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
          
            </div>
         
            <div class="box-body">
                
              <div id="container2" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
              
            </div>
          
          </div>
    </div>
</div>
-->
<?php   
    function intervalo_dias($fecha_inicial, $fecha_final, $sw_control) {
        
            $dias = array('Mon'=>'Lun', 'Tue'=>'Mar', 'Wed'=>'Mie', 'Thu'=>'Jue', 'Fri'=>'Vie');
            $fecha1 = strtotime($fecha_inicial); 
            $fecha2 = strtotime($fecha_final);
            $array_data = array();

            $j = 0;
            for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
                if((strcmp(date('D',$fecha1),'Sun')!=0) and (strcmp(date('D',$fecha1),'Sat')!=0)){	
                    array_push($array_data, date('m-d',$fecha1)." ".$dias[date('D',$fecha1)]);
                    $j++;
                }
            }
            
            if ($sw_control == 1) {
                
                return $j;
                
            }else if ($sw_control == 2) {
                
               return json_encode($array_data);
               
            }
    }

    function ideal_burn($horas, $fecha_inicial, $fecha_final) {

        $datos = array();
        $suma_horas = $horas;


        $dias = intervalo_dias($fecha_inicial, $fecha_final, 1);
        $resta = ($horas / $dias);

        for ($i = 1; $i <= $dias; $i++) {

            $suma_horas = round($suma_horas - $resta, 2);


            array_push($datos, $suma_horas);
        }

        return json_encode($datos);
    }

    foreach ($consulta_acutal_burn as $key => $value) {

        $fecha1 = strtotime($array_actual['fecha_desde']); 
        $fecha2 = strtotime($value['fecha_terminado']);
        $j = 0;
        
        for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
            if((strcmp(date('D',$fecha1),'Sun')!=0) and (strcmp(date('D',$fecha1),'Sat')!=0)){	
                $j++;
            }
        }
         
        $consulta_acutal_burn[$key]['dias'] = $j;
    }

    $arreglo_actual_burn = array();
    $total_tiempo_desarrollo = $consulta_tiempo_desarrollo; //120 Horas
    $contador = 0;
    $total_dias_sprint = intervalo_dias($array_actual['fecha_desde'], $array_actual['fecha_hasta'], 1); // 17 Dias


    for ($i = 1; $i <= $total_dias_sprint; $i++) {

        foreach ($consulta_acutal_burn as $value2) {

            if ($i == $value2['dias']) {


                $total_tiempo_desarrollo = $total_tiempo_desarrollo - $value2['sum_horas'];
                $contador++;
            }
        }

        $arreglo_actual_burn[] = (int) $total_tiempo_desarrollo;

        if ($contador == count($consulta_acutal_burn)) {

            break;
        }
    }
    
    
    function arreglo_barchart($barChart) {
        // arreglo de dias
        
    $grafica = array();
        foreach ($barChart as $value2) {
            $grafica[] = array(
                $value2['nombres'],
                (($value2['tiempo_terminado']*100)/$value2['tiempo_total'])
            );
        }
        
    return json_encode($grafica);
    }
    

    $datos_ideal_burn = ideal_burn($consulta_tiempo_desarrollo, $array_actual['fecha_desde'], $array_actual['fecha_hasta']);
    $arreglo_dias = intervalo_dias($array_actual['fecha_desde'], $array_actual['fecha_hasta'], 2);
    $json_actual_burn = json_encode($arreglo_actual_burn);
    
    $datos_barChart = arreglo_barchart($barChart);

    $this->registerJs("

        $('#container').highcharts({
        title: {
          text: '$titulo',
          x: -20 //center
        },
        colors: ['blue', 'red'],
        plotOptions: {
          line: {
            lineWidth: 3
          },
          tooltip: {
            hideDelay: 200
          }
        },
        subtitle: {
          text: '$subtitulo',
          x: -20
        },
        xAxis: {
          categories: $arreglo_dias
        },
        yAxis: {
          title: {
            text: 'Horas'
          },
          plotLines: [{
            value: 0,
            width: 1
          }]
        },
        tooltip: {
          valueSuffix: ' hrs',
          crosshairs: true,
          shared: true
        },
        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle',
          borderWidth: 0
        },
        series: [{
          name: 'Ideal Burn',
          color: 'rgba(255,0,0,0.25)',
          lineWidth: 2,
          data: $datos_ideal_burn
        },
        {
          name: 'Actual Burn',
          color: 'rgba(0,120,200,0.75)',
          marker: {
            radius: 6
          },
          //data: [100, 110, 85, 60, 60, 30, 32, 23, 9, 2]
         data:$json_actual_burn 
        }]
      });

    ");  
    
    $this->registerJs("
            Highcharts.chart('container2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Titulo'
                },
                subtitle: {

                },
                xAxis: {
                    type: 'category',
                    labels: {
                        //rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Horas'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: 'Porcentaje Horas Terminadas: <b>{point.y:.1f}% Horas</b>'
                },
                series: [{
                    name: 'Population',
                    data: $datos_barChart,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        //rotation: -90,
                        //color: '#FFFFFF',
                        align: 'center',
                        format: '{point.y:.1f}%', // one decimal
                        y: 1, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });   
    ");
?>
<?php Pjax::end(); ?>