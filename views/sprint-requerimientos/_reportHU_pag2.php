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
        
    }
    
    table {
        border-collapse: collapse;
    }
    
    #div-encabezado{
        height: 8%;
        /*background-color: yellow;*/
    }
    
    #div-pruebas1{
        height: 20%;
        /*background-color: wheat;*/  
    }
    
    #div-pruebas2{
        height: 20%;
        /*background-color: greenyellow;*/  
    }
    
    #div-soporte{
        height: 20%;
        /*background-color: tomato;*/ 
    }
    
    #div-produccion1{
        height: 10%;
        /*background-color: yellowgreen;*/ 
    }
    #div-produccion2{
        height: 10%;
        /*background-color: slateblue;*/
    }
    
    .td-placeholder{
        text-align: center;
        color: #B4BAC7;
        font-weight:bold;
        /*text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;*/
        
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
        <td style="text-align:center;">Fecha: <?= date("Y-m-d") ?></td>
      </tr>
    </table>
</div>
<div id="div-pruebas1">
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align:left; background-color: #B4BAC7;">Pruebas Funcionales</th>
                <th colspan="2" style="text-align:center; background-color: #B4BAC7;">Aprobado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="2%" style="text-align:center;">#</td>
                <td width="25%">Fecha de Entrega</td>
                <td width="25%">Fecha de Prueba</td>
                <td width="15%" style="text-align:center;">Si</td>
                <td width="15%" style="text-align:center;">No</td>
            </tr>
           
            <?php 
            
            $html = "";
            
            if ( !empty($obj_pruebas) ){
                
                $count = 1;
                
                foreach ($obj_pruebas as $clave => $valor) {
                    

                    $html .= "<tr>";
                    $html .= "  <td style='text-align:center;'>".($count)."</td>"; 
                    $html .= "  <td>".$valor->fecha_entrega."</td>";   
                    $html .= "  <td>".$valor->fecha_prueba."</td>"; 
                    $html .= "  <td style='text-align:center;'>".($valor->estado == 1 ? 'x' : '&nbsp;')."</td>";
                    $html .= "  <td style='text-align:center;'>".($valor->estado == 0 ? 'x' : '&nbsp;')."</td>";  
                    $html .= "</tr>";
                    $count++;

                }
                
            
            }else{
                
                
                    $html .= "<tr>";
                    $html .= "  <td style='text-align:center;'>1</td>"; 
                    $html .= "  <td>&nbsp;</td>";   
                    $html .= "  <td>&nbsp;</td>"; 
                    $html .= "  <td style='text-align:center;'>&nbsp;</td>";
                    $html .= "  <td style='text-align:center;'>&nbsp;</td>";  
                    $html .= "</tr>";
                    $html .= "<tr>";
                    $html .= "  <td style='text-align:center;'>2</td>"; 
                    $html .= "  <td>&nbsp;</td>";   
                    $html .= "  <td>&nbsp;</td>"; 
                    $html .= "  <td style='text-align:center;'>&nbsp;</td>";
                    $html .= "  <td style='text-align:center;'>&nbsp;</td>";  
                    $html .= "</tr>";
                    $html .= "<tr>";
                    $html .= "  <td style='text-align:center;'>3</td>"; 
                    $html .= "  <td>&nbsp;</td>";   
                    $html .= "  <td>&nbsp;</td>"; 
                    $html .= "  <td style='text-align:center;'>&nbsp;</td>";
                    $html .= "  <td style='text-align:center;'>&nbsp;</td>";  
                    $html .= "</tr>";
                
                
            }
            
            echo $html;
            
            ?>
            
           
        </tbody>
    </table> 
</div>
<div id="div-pruebas2">
    <table border="1" style="width:100%">
        <tbody>
            <tr>
                <td width="2%" colspan="2" style="text-align:left;"><b>Ingeniero de Pruebas</b></td>
                <td width="2%" colspan="2" style="text-align:left;"><b>Usuario Recibe Requerimiento</b></td>
            </tr>
            <tr>
                <?php    
                    if ( empty($obj_pruebas) ){

                        echo '<td width="35%">';
                        echo 'Nombre';
                        echo '</td>';

                    }else{

                        echo '<td width="35%">';
                        echo    end($obj_pruebas)->usuarioPruebas->nombreCompleto;
                        echo '</td>';


                    }
                ?>    
                <td class="td-placeholder" width="15%">
                    Firma
                </td>  
                <?php
                    if ( empty($obj_requerimientos_implementacion->usuarioRecibe) ){
                    
                        echo '<td width="35%" class="td-placeholder">';
                        echo '  Nombre';
                        echo '</td>';

                    }else{
                        
                        echo '<td width="35%">';
                        echo    $obj_requerimientos_implementacion->usuarioRecibe->nombreCompleto;
                        echo '</td>';
                    }
                ?>
                <td class="td-placeholder" width="15%">Firma</td>
            </tr>
        </tbody>
    </table> 
    <p>
        <b>Nivel de satisfacción</b>(Siendo 1 No satisfecho y 5 muy satisfecho) |1|2|3|4|5|
    </p>
    <p>
        <b>Observaciones adicionales:</b>___________________________________________________________________________________________________
        <br>
        _________________________________________________________________________________________________________________________________
        <br>
        _________________________________________________________________________________________________________________________________
    </p>
</div>
<div id="div-implementacion">
    
    <div id="div-soporte">
        
        <table border="1" style="width:100%">
            <thead>
                <tr>
                    <th colspan="3" style="text-align:center; background-color: #B4BAC7;">Implementación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="95%" colspan="2" style="text-align:center;">Capacitación a Soporte</th>
                    <th width="5%" style="text-align:center;">Fecha de Entrega</th>
                </tr>
                <tr>
                    <?php
                        if ( empty($obj_requerimientos_implementacion->soporteEntregadoPor) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '<p style="font-size: 9px;">Entregado Por:</p>';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->soporteEntregadoPor->nombreCompleto;
                            echo '</td>';
                        }
                    ?>
                    <td class="td-placeholder" width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        
                        <?= (empty($obj_requerimientos_implementacion->soporte_entregado_fecha) ? "" : $obj_requerimientos_implementacion->soporte_entregado_fecha) ?>
 
                    </td>
                </tr>
                <tr>
                        
                    <?php
                        if ( empty($obj_requerimientos_implementacion->soporte1RecibioCapacitacion) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '<p style="font-size: 9px;">Recibió Capacitación:</p>';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->soporte1RecibioCapacitacion->nombreCompleto;
                            echo '</td>';
                        }
                    ?>
                    <td class="td-placeholder" width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        
                        
                        <?= (empty($obj_requerimientos_implementacion->soporte1_fecha_entrega) ? "" : $obj_requerimientos_implementacion->soporte1_fecha_entrega) ?>

                    </td>
                </tr>
                <tr>
                    <?php
                        if ( empty($obj_requerimientos_implementacion->soporte2RecibioCapacitacion) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '<p style="font-size: 9px;">Recibió Capacitación:</p>';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->soporte2RecibioCapacitacion->nombreCompleto;
                            echo '</td>';
                        }
                    ?>
                    <td class="td-placeholder" width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        
                        <?= (empty($obj_requerimientos_implementacion->soporte2_fecha_entrega) ? "" : $obj_requerimientos_implementacion->soporte2_fecha_entrega) ?>

                    </td>
                </tr>
            </tbody>
        </table>
        
        
        
    </div>
    <div id="div-produccion1">
        
        <table border="1" style="width:100%">
            <tbody>
                <tr>
                    <td width="50%" style="text-align:left;">Usuario Aprueba Actualización en Producción</td>
                    <td width="50%" style="text-align:center;">Fecha Sugerida Actualización</td>
                </tr>
                <tr>     
                    <?php
                        if ( empty($obj_requerimientos_implementacion->usuarioApruebaProduccion) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->usuarioApruebaProduccion->nombreCompleto;
                            echo '</td>';
                        }
                    ?>
                    <td width="5%" style="text-align:center;">
                        
                        <?= (empty($obj_requerimientos_implementacion->fecha_subida_produccion) ? "" : $obj_requerimientos_implementacion->fecha_subida_produccion) ?>
             
                    </td>
                </tr>
            </tbody>
        </table>
        
    </div>
    <div id="div-produccion2">
        
        <table border="1" style="width:100%">
            <tbody>
                <tr>
                    <th width="95%" colspan="2" style="text-align:center;">Actualización en Producción</th>
                    <th width="5%" style="text-align:center;">Fecha de Entrega</th>
                </tr>
                <tr>
                    <?php
                        if ( empty($obj_requerimientos_implementacion->produccionEntregadoPor) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '  <p style="font-size: 9px;">Entregado Por:</p>';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->produccionEntregadoPor->nombreCompleto;
                            echo '</td>';
                        }
                    ?>
                    <td class="td-placeholder" width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        
                        <?= (empty($obj_requerimientos_implementacion->produccion_entregado_fecha) ? "" : $obj_requerimientos_implementacion->produccion_entregado_fecha) ?>
                        
                    </td>
                </tr>
                <tr>
                    <?php
                        if ( empty($obj_requerimientos_implementacion->produccion1RecibioCapacitacion) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '  <p style="font-size: 9px;">Recibio:</p>';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->produccion1RecibioCapacitacion->nombreCompleto;
                            echo '</td>';
                        }
                    ?>
                    <td class="td-placeholder" width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        
                        
                        <?= (empty($obj_requerimientos_implementacion->produccion1_fecha_entrega) ? "" : $obj_requerimientos_implementacion->produccion1_fecha_entrega) ?>

                    </td>
                </tr>
                <tr>
                    <?php
                        if ( empty($obj_requerimientos_implementacion->produccion2RecibioCapacitacion) ){

                            echo '<td width="90" class="td-placeholder" style="text-align:center;">';
                            echo '  <p style="font-size: 9px;">Recibio:</p>';
                            echo '  Nombre';
                            echo '</td>';

                        }else{

                            echo '<td width="90" style="text-align:left;">';
                            echo    $obj_requerimientos_implementacion->produccion2RecibioCapacitacion->nombreCompleto;
                            echo '</td>';
                        }
                    ?>   
                    <td class="td-placeholder" width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        
                        <?= (empty($obj_requerimientos_implementacion->produccion2_fecha_entrega) ? "" : $obj_requerimientos_implementacion->produccion2_fecha_entrega) ?>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>