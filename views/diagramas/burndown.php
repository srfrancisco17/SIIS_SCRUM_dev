<?php

use app\assets\HighchartsAssets;

HighchartsAssets::register($this);
$this->title = 'Burndown';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php

    function intervalo_dias($fecha_inicial, $fecha_final, $sw_control){

        $datetime1 = date_create($fecha_inicial);
        $datetime2 = date_create($fecha_final);
        $interval = date_diff($datetime1, $datetime2);

        $dias = $interval->format('%a');

        if ($sw_control == 1){
            return $dias;
        }else if($sw_control == 2){

            $array_data = array();

                for ($i = 1; $i <= $dias; $i++) {

                    array_push($array_data, 'Dia '.$i);

                }
            return json_encode($array_data);
        }

    }


    function ideal_burn($horas, $fecha_inicial, $fecha_final)
    {

        $datos = array();
        $suma_horas = $horas;
        

        $dias = intervalo_dias($fecha_inicial, $fecha_final, 1);
        $resta = ($horas/$dias); 

        for ($i = 1; $i <= $dias; $i++) {

            $suma_horas = round($suma_horas-$resta,2);


            array_push($datos, $suma_horas);
        }

        return json_encode($datos);
    }

//echo '<pre>';
//print_r($consulta_tiempo_desarrollo);
////print_r($datos_ideal_burn = json_decode(ideal_burn($consulta_burndown->horas_desarrollo, $consulta_burndown->sprint->fecha_desde, $consulta_burndown->sprint->fecha_hasta)));
//echo '</pre>';
//exit();

$datos_ideal_burn = ideal_burn($consulta_tiempo_desarrollo, $consulta_burndown->sprint->fecha_desde, $consulta_burndown->sprint->fecha_hasta);
$arreglo_dias = intervalo_dias($consulta_burndown->sprint->fecha_desde, $consulta_burndown->sprint->fecha_hasta, 2);

$this->registerJs("
    
    $('#container').highcharts({
    title: {
      text: 'Burndown Chart',
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
      text: 'sub-titulo',
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
    }]
  });

");
?>