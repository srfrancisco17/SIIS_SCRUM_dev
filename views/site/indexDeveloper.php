<?php

    use app\assets\HighchartsAssets;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use kartik\select2\Select2;
    use app\models\Sprints;
    
    HighchartsAssets::register($this);
    $this->title = 'Dashboard';
    $this->params['breadcrumbs'][] = $this->title;
    
    /*
     * $sprint_horas_desarrollo = 
     * El calculo es el tiempo total de las tareas de los requerimientos dentro de un sprint especifico
     */
    $sprint_id = $obj_sprint['sprint_id'];
    $sprint_alias = $obj_sprint['sprint_alias'];
    $sprint_horas_desarrollo = $obj_sprint['horas_desarrollo'];
    $sprint_fecha_desde = $obj_sprint['fecha_desde'];
    $sprint_fecha_hasta = $obj_sprint['fecha_hasta'];
    
    $horas_establecidas = $obj_sprint_usuario['horas_establecidas'];

    $porcentaje_productividad = 0;
    
?>
<?php Pjax::begin(); ?>
<div class="row">
    <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <!--<h3 class="box-title">Burndown</h3>-->
                <div class="row">
                    <?= Html::beginForm(['site/index-developer'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
                    <div class="col-lg-3">
                        <?= Select2::widget([
                            'name' => 'sprint_id',
                            'size' => Select2::MEDIUM,
                            //'value' => Yii::$app->request->post('sprint_id', $array_sprints[sizeof($array_sprints)-1]['sprint_id']), // value to initialize
                            'value' => Yii::$app->request->post('sprint_id', $sprint_id),
                            'data' => Sprints::getListaSprints(),
                        ])?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::submitButton('Cargar', ['class' => 'btn btn-md btn-primary', 'name' => 'hash-button']) ?>
                    </div>
                    <?= Html::endForm() ?>
                </div>  
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                    <?php
                    
                        if (!empty($consulta_acutal_burn)){
                            
                    ?>
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    <?php
                        }else{
                    ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
                            Actualmente no esta disponible la grafica
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
    <div class="col-lg-4">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
                    <?php
                        if (!empty($barChart)){
                    ?>
                            <div id="container3" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                    <?php
                        }else{
                    ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
                            Actualmente no esta disponible la grafica
                        </div>
                    <?php    
                        }
                    ?>
            </div>
        </div>
    </div>
</div>
<?php

if (!empty($consulta_acutal_burn)){
    
    $dias_festivos = array(
        '2017-07-20', 
        '2017-08-07', 
        '2017-08-21', 
        '2017-10-16', 
        '2017-11-06', 
        '2017-11-13', 
        '2017-12-08', 
        '2017-12-25', 
        '2018-01-01', 
        '2018-01-08',
        '2018-03-19',
        '2018-03-25',
        '2018-03-29',
        '2018-03-30',
        '2018-04-01',
        '2018-05-01',
        '2018-05-14'
    );
    
    function intervalo_dias($fecha_inicial, $fecha_final, $sw_control, $dias_festivos_p) {

        $dias = array('Mon'=>'Lun', 'Tue'=>'Mar', 'Wed'=>'Mie', 'Thu'=>'Jue', 'Fri'=>'Vie');
        $fecha1 = strtotime($fecha_inicial); 
        $fecha2 = strtotime($fecha_final);
        $array_data = array();

        $j = 0;

        for($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))){ 
            if((strcmp(date('D',$fecha1),'Sun')!=0) and (strcmp(date('D',$fecha1),'Sat')!=0)){  
                if (!(in_array(date('Y-m-d',$fecha1), $dias_festivos_p))) {
                    array_push($array_data, date('m-d',$fecha1)." ".$dias[date('D',$fecha1)]);
                    $j++;
                }
            }
        }
        if ($sw_control == 1) {
            return $j;
            
        }else if ($sw_control == 2) {
           return json_encode($array_data);   
        }
    }
    
    /*
     * funcion ideal_burn
     */
    function ideal_burn($horas, $fecha_inicial, $fecha_final, $dias_festivos_p) {

        $datos = array();
        $suma_horas = $horas;


        $dias = intervalo_dias($fecha_inicial, $fecha_final, 1, $dias_festivos_p);
        $resta = ($horas / $dias);

        for ($i = 1; $i <= $dias; $i++) {

            $suma_horas = round($suma_horas - $resta, 2);


            array_push($datos, $suma_horas);
        }
        return json_encode($datos);
    }
    
    function actual_burn($consulta_acutal_burn , $fecha_desde, $dias_festivos, $sprint_total_dias, $horas) {
        
        foreach ($consulta_acutal_burn as $key => $value) {
            $fecha1 = strtotime($fecha_desde); 
            $fecha2 = strtotime($value['fecha_terminado']);
            $j = 0;
            for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
                if((strcmp(date('D',$fecha1),'Sun')!=0) and (strcmp(date('D',$fecha1),'Sat')!=0)){
                    if (!(in_array(date('Y-m-d',$fecha1), $dias_festivos))) {
                        $j++;
                    }
                }
            }
            $consulta_acutal_burn[$key]['dias'] = $j;            
        }
         
        $arreglo_actual_burn = array();
        $contador = 0;
        
        for ($i = 1; $i <= $sprint_total_dias; $i++) {

            foreach ($consulta_acutal_burn as $value2) {

                if ($i == $value2['dias']) {


                    $horas = $horas - $value2['sum_horas'];
                    $contador++;
                }
            }

            $arreglo_actual_burn[] = (int) $horas;

            if ($contador == count($consulta_acutal_burn)) {

                break;
            }
        }

        return $arreglo_actual_burn;
        
    }
    
/*
     * GRAFICA DEL BURNDOWN 
     */
        
    $sprint_total_dias = intervalo_dias($sprint_fecha_desde, $sprint_fecha_hasta, 1, $dias_festivos);
    
    $datos_ideal_burn = ideal_burn($total_tiempo_calculado, $sprint_fecha_desde, $sprint_fecha_hasta, $dias_festivos);
    $datos_actual_burn = actual_burn($consulta_acutal_burn, $sprint_fecha_desde, $dias_festivos, $sprint_total_dias, $total_tiempo_calculado);
    $arreglo_dias = intervalo_dias($sprint_fecha_desde, $sprint_fecha_hasta, 2, $dias_festivos);
    
    $titulo = 'Sprint: '.$sprint_alias.' - '.$total_tiempo_calculado.' Horas';
    $subtitulo = '('.$sprint_fecha_desde.') - ('.$sprint_fecha_hasta.')'; 
    
    
    $porcentaje_productividad = number_format(((count($datos_actual_burn))*100)/$sprint_total_dias, 1);
    
    
    
//    var_dump($porcentaje_productividad);
//    exit;
    
    
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
         data:".json_encode($datos_actual_burn)."  
        }]
      });

        ");
 
}

if (!empty($barChart)){
    
    function arreglo_barchart($barChart, $horas_establecidas){
            
            $grafica = array();

            $tiempo_terminado = $barChart['tiempo_terminado'];
            
            if (is_null($tiempo_terminado) || is_null($horas_establecidas)){
                
                $grafica[] = array('', 0);
                
            }else{
                
                $grafica[] = array(
                    $barChart['nombres'].' '.$barChart['apellidos'].'-'.$horas_establecidas.'-'.$tiempo_terminado,
                    (($tiempo_terminado*100)/$horas_establecidas)
                );
                
            }

            return json_encode($grafica);
              
        }
    
    
    /*
     * GRAFICA DEL BARCHART EL PORCENTAJE DE HORAS TERMINADAS
     */
    $datos_barChart = arreglo_barchart($barChart, $horas_establecidas);

    $this->registerJs("
            Highcharts.chart('container3', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Productividad ".$porcentaje_productividad."%'
                },
                colors: ['#F56954'],
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
 
}

  
?>
<?php Pjax::end(); ?>
