<?php

    use app\assets\HighchartsAssets;

    HighchartsAssets::register($this);
    $this->title = 'Dashboard';
    $this->params['breadcrumbs'][] = $this->title;

    
    
//    echo '<pre>';
//    var_dump($consulta_ideal_burn);
//    echo '</pre>';
//    exit();    

    if (!empty($consulta_ideal_burn)){
    
    
    
        function intervalo_dias($fecha_inicial, $fecha_final, $sw_control) {

            $datetime1 = date_create($fecha_inicial);
            $datetime2 = date_create($fecha_final);
            $interval = date_diff($datetime1, $datetime2);

            $dias = $interval->format('%a')+1;

            if ($sw_control == 1) {
                return $dias;
            } else if ($sw_control == 2) {

                $array_data = array();

                for ($i = 1; $i <= $dias; $i++) {

                    array_push($array_data, 'Dia ' . $i);
                }
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


            $datetime1 = date_create($consulta_ideal_burn->sprint->fecha_desde);
            $datetime2 = date_create($value['fecha_terminado']);
            $interval = date_diff($datetime1, $datetime2);

            $dias = $interval->format('%a')+1;

            $consulta_acutal_burn[$key]['dias'] = $dias;
        }


        $arreglo_actual_burn = array();
        $total_tiempo_desarrollo = $consulta_tiempo_desarrollo; //120 Horas
        $contador = 0;
        $total_dias_sprint = intervalo_dias($consulta_ideal_burn->sprint->fecha_desde, $consulta_ideal_burn->sprint->fecha_hasta, 1); // 17 Dias


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


        $datos_ideal_burn = ideal_burn($consulta_tiempo_desarrollo, $consulta_ideal_burn->sprint->fecha_desde, $consulta_ideal_burn->sprint->fecha_hasta);
        $arreglo_dias = intervalo_dias($consulta_ideal_burn->sprint->fecha_desde, $consulta_ideal_burn->sprint->fecha_hasta, 2);
        $json_actual_burn = json_encode($arreglo_actual_burn);

        $titulo = $consulta_ideal_burn->sprint->sprint_alias;
        $subtitulo = '('.$consulta_ideal_burn->sprint->fecha_desde.') - ('.$consulta_ideal_burn->sprint->fecha_hasta.')';


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
        
    }   
?>

<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Burndown</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
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
                    <?php
                    
                        if (!empty($consulta_ideal_burn)){
                            
                    ?>
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    <?php
                        }else{
                    ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
                            Oops algo a salido mal (๏̯๏).
                        </div>
                        
                    <?php    
                        }
                    ?>
                    
                </div>
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <br>
                    <h5 class="description-header"><?= $consulta_total_requerimientos?></h5>
                    <span class="description-text">Total Requerimientos</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <br>
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
                    <!--                    
                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                    -->
                    <br>
                    <h5 class="description-header"><?= $consulta_total_tareas_terminadas ?></h5>
                    <span class="description-text">Total Tareas Terminadas</span>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
<!--
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Browser Usage</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
              
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="chart-responsive">
                        <canvas id="pieChart" height="160" width="257" style="width: 257px; height: 160px;"></canvas>
                      </div>
                     
                    </div>
               
                    <div class="col-md-4">
                      <ul class="chart-legend clearfix">
                        <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                        <li><i class="fa fa-circle-o text-green"></i> IE</li>
                        <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                        <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                        <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                        <li><i class="fa fa-circle-o text-gray"></i> Navigator</li>
                      </ul>
                    </div>
                 
                  </div>
          
                </div>
           
                <div class="box-footer no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li><a href="#">United States of America
                      <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                    <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                    </li>
                    <li><a href="#">China
                      <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> 0%</span></a></li>
                  </ul>
                </div>
            
        </div>
    </div>
</div>
-->