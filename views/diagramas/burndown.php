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
    

    foreach ($consulta_acutal_burn as $key => $value) {
        
        
        $datetime1 = date_create($consulta_ideal_burn->sprint->fecha_desde);
        $datetime2 = date_create($value['fecha_terminado']);
        $interval = date_diff($datetime1, $datetime2);

        $dias = $interval->format('%a');
        
        $consulta_acutal_burn[$key]['dias'] = $dias;
        

    }

    /*
     * 
     */
    
    $arreglo_actual_burn = array();
    $total_tiempo_desarrollo = $consulta_tiempo_desarrollo; //120 Horas
    $contador = 0;
    $total_dias_sprint = intervalo_dias($consulta_ideal_burn->sprint->fecha_desde, $consulta_ideal_burn->sprint->fecha_hasta, 1); // 17 Dias
    
   
    for ($i = 1; $i < $total_dias_sprint; $i++) {

        foreach ($consulta_acutal_burn as $value2) {
            
                if ($i == $value2['dias']) {

                    
                    $total_tiempo_desarrollo = $total_tiempo_desarrollo - $value2['sum_horas'];
                    $contador++;
                        
		}


        }
        
        $arreglo_actual_burn[] = (int)$total_tiempo_desarrollo;

            if ($contador == count($consulta_acutal_burn)) {

                break;
            }

        
    }
    
    //$datos_actual_burn = json_encode($arreglo_actual_burn);
    
    $json_actual_burn = json_encode($arreglo_actual_burn);
    
//    echo '<pre>';
//    var_dump($arreglo_actual_burn[1]);
//    echo '</pre>';
//    
//    echo '<pre>';
//        print_r($json_actual_burn);
//    echo '</pre>';
//    echo '<br>';
//    echo '<pre>';
//        print_r($consulta_acutal_burn);
//    echo '</pre>';
    

$datos_ideal_burn = ideal_burn($consulta_tiempo_desarrollo, $consulta_ideal_burn->sprint->fecha_desde, $consulta_ideal_burn->sprint->fecha_hasta);
$arreglo_dias = intervalo_dias($consulta_ideal_burn->sprint->fecha_desde, $consulta_ideal_burn->sprint->fecha_hasta, 2);

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
?>