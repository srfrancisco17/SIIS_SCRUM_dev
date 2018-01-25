<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>
<style>
    * {
      margin:  0;
      padding: 0;
    }
    
    body{
        font-size: 12px;
        font-family: "Times New Roman", sans-serif;
    }

    table {
        border-collapse: collapse;
    }
    
    #div-encabezado{
        height: 8%;
        /*background-color: yellow;*/
    }
    
    #div-informacionHU{
        height: 9%;
        /*background-color: greenyellow;*/
    }
    
    #div-historia_usuario{
        height: 15%;
        /*background-color: blue;*/
        font-style: normal;
        color: white;
    }
    
    #div-criterios_aceptacion{
        height: 40%;
        /*background-color: red;*/
    }
    
    #div-procesos_involucrados{
        height: 9%;
        /*background-color: green;*/
    } 
    #div-usuarios_impactados{
        height: 9%;
        /*background-color: violet;*/
    }
    
    #div-plan_divulgacion{
        height: 9%;
        /*background-color: skyblue;*/   
    }
</style>
<div id="div-encabezado">
    <table border="1" style="width:100%">
      <tr>
          <td style="text-align:center;">
                <?= Html::img('@web/img/icono-cdo.png', ['alt' => 'My logo', 'style' => ['width' => '45px', 'height' => '45px']]) ?>
          </td>
          <td style="text-align:center; font-weight:bold;">
            SISTEMA DE INFORMACION INTEGRAL EN SALUD SIIS
            <br>
            Lista de Chequeo Aprobación Final de Requerimientos
        </td> 
        <td style="text-align:center;font-size: 12px;">Fecha: <?= date("Y-m-d") ?></td>
      </tr>
    </table>
</div>
<div id="div-informacionHU">
    <table style="width:100%;border: 1px solid black;">
        <tr>
            <th width="350px"  colspan="2" style="text-align:left;">Solicitud:</th>
            <th colspan="2" style="text-align:left;">Desarrollo:</th> 
        </tr>
        <tr>
            <td>Por:</td>
            <td>
                <?= $obj_requerimiento->requerimiento->usuarioSolicita->nombreCompleto ?>
            </td>
            <td>Por:</td>
            <td>
              <?= $obj_requerimiento->usuarioAsignado->nombreCompleto ?>
            </td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td>
                <?= $obj_requerimiento->requerimiento->fecha_requerimiento ?>
            </td>
            <td>Fecha Asignación:</td>
            <td>
                <?= explode(" ", $obj_requerimiento->fecha_asignacion)[0] ?>
            </td>
        </tr>
        <tr>
            <td>Dpto:</td>
            <td>
                <?= $obj_requerimiento->requerimiento->departamentoSolicita->descripcion ?>
            </td>
            <td>Fecha Terminación:</td>
            <td>
                <input type="text" name="fecha_terminacion" size="17" style="border:0px;">       
            </td>
        </tr>
    </table>
    
</div>
<div id="div-historia_usuario">
 
    <table border="1" style="width:100%">
      <tr>
          <th colspan="3" style="background-color: #B4BAC7;">Historia de usuario:</th>
      </tr>
      <tr>
          <td colspan="3"><?= $obj_requerimiento->requerimiento->requerimiento_titulo ?></td>
      </tr>
      <tr>
        <th>Como (Rol):</th>
        <th>Necesito (Funcionalidad):</th> 
        <th>Para (Finalidad):</th>
      </tr>
      <tr>
          <td style="height: 75px;">
              <?= $obj_requerimiento->requerimiento->requerimiento_descripcion ?>
          </td>
          <td>
                <?= $obj_requerimiento->requerimiento->requerimiento_funcionalidad ?>
          </td> 
          <td>
                <?= $obj_requerimiento->requerimiento->requerimiento_justificacion ?>
          </td>
      </tr>
    </table>
    
</div>
<div id="div-criterios_aceptacion">
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th colspan="2" style="text-align:left;background-color: #B4BAC7;">Criterios de Aceptación</th>
                <th colspan="3" style="text-align:center;background-color: #B4BAC7;">Aprobado</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $html = "";
                if (!empty($datos_tareas)){
                    
                    foreach ($datos_tareas as $clave_tareas => $valor_tareas) {

                        $html .= "<tr>";
                        $html .= "  <td width='2%' style='text-align:center;'>";
                        $html .=        ($clave_tareas+1);
                        $html .= "  </td>";

                        $html .= "  <td width='85%'>";
                        $html .=        $valor_tareas['tarea_titulo'];
                        $html .= "  </td>";

                        for ($i = 0; $i < 3; $i++) {

                            $html .= "  <td width='2%' style='text-align:center;'>";

                            if ( !empty( $valor_tareas['tareas_pruebas'][$i]['estado']) ){

                                if ($valor_tareas['tareas_pruebas'][$i]['estado'] == '1'){
                                    $html .= "x";
                                }
                            }else{
                                $html .= "&nbsp;";
                            }
                            $html .= "  </td>";
                        }
                        $html .= "</tr>";
                    }
                }else{

                    for ($i = 1; $i <= 5; $i++) {
                    
                        $html .= "<tr>";
                        $html .= "  <td width='2%' style='text-align:center;'>";
                        $html .=        $i;
                        $html .= "  </td>";

                        $html .= "  <td width='85%'>";
                        $html .= "      &nbsp;";
                        $html .= "  </td>";
                    
                        $html .= "  <td width='2%' style='text-align:center;'>";
                        $html .= "      &nbsp;";
                        $html .= "  </td>";
                    
                        $html .= "  <td width='2%' style='text-align:center;'>";
                        $html .= "      &nbsp;";
                        $html .= "  </td>";
                        
                        $html .= "  <td width='2%' style='text-align:center;'>";
                        $html .= "      &nbsp;";
                        $html .= "  </td>";
                    }  
                }
                echo $html;
            ?>
        </tbody>
    </table> 
</div>
<div id="div-procesos_involucrados">
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align:left; background-color: #B4BAC7;"> Procesos Involucrados</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
                $html = "";
                $html2 = "";

                for ($i = 1; $i <= 9; $i++) {

                    
                    $html2 .= "<td width='33%'>";
                    if ( empty($obj_perfiles_impactados[($i-1)]) ){
                        
                        $html2 .= $i.".";
                        
                    }else{
                        $html2 .= $i.". ".$obj_perfiles_impactados[($i-1)]["descripcion"];
                    }
                    $html2 .= "</td>";
                    
                    if (($i % 3) == 0){
                        $html .= "<tr>";
                        $html .=    $html2; 
                        $html .= "</tr>";
                        $html2 = "";
                    }
                }
                
                echo $html;
            ?>
        </tbody>
    </table> 
</div>
<div id="div-usuarios_impactados">
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align:left;background-color: #B4BAC7;"> Perfil de Usuarios que impacta</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
                $html = "";
                $html2 = "";

                for ($i = 1; $i <= 9; $i++) {
                    
                    $html2 .= "<td width='33%'>";
                    if ( empty($obj_procesos_involucrados[($i-1)]) ){
                        
                        $html2 .= $i.".";
                        
                    }else{
                        $html2 .= $i.". ".$obj_procesos_involucrados[($i-1)]["descripcion"];
                    }
                    $html2 .= "</td>";
                    
                    if (($i % 3) == 0){
  
                        $html .= "<tr>";
                        $html .=    $html2; 
                        $html .= "</tr>";
                        $html2 = "";
                    }
                }
                
                echo $html;
            ?>
        </tbody>
    </table>  
</div>
<div id="div-plan_divulgacion">
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align:left;background-color: #B4BAC7;">Plan de divulgación</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="2%" style="text-align:center;">0</td>
                <td width="90%">No requiere</td>
                <td width="10%" style="text-align:center;"><?= ($obj_requerimiento->requerimiento->divulgacion === '0') ? "x" : "&nbsp;" ?></td>
            </tr>
            <tr>
                <td width="2%" style="text-align:center;">1</td>
                <td width="90%">Informativo</td>
                <td width="10%" style="text-align:center;"><?= ($obj_requerimiento->requerimiento->divulgacion === '1') ? "x" : "&nbsp;" ?></td>
            </tr>
            <tr>
                <td width="2%" style="text-align:center;">2</td>
                <td width="90%">Capacitación formal</td>
                <td width="10%" style="text-align:center;"><?= ($obj_requerimiento->requerimiento->divulgacion === '2') ? "x" : "&nbsp;" ?></td>
            </tr>
        </tbody>
    </table> 
</div>
<?php

//exit;
?>
