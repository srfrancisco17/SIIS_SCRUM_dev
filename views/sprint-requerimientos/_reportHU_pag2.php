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
        background-color: yellow;
    }
    
    #div-pruebas1{
        height: 20%;
        background-color: wheat;  
    }
    
    #div-pruebas2{
        height: 20%;
        /*background-color: greenyellow;*/  
    }
    
    #div-soporte{
        height: 20%;
        background-color: tomato; 
    }
    
    #div-produccion1{
        height: 8%;
        background-color: yellowgreen; 
    }
    #div-produccion2{
        height: 20%;
        background-color: slateblue;
    }
    
    .td-placeholder{
        text-align: center;
        color: #F0F0F0;
    }
    
</style>
<div id="div-encabezado">
    
    <table border="1" style="width:100%">
      <tr>
          <td style="text-align:center;">
                <?= Html::img('@web/img/icono-cdo.png', ['alt' => 'My logo', 'style' => ['width' => '45px', 'height' => '45px']]) ?>
          </td>
          <td style="text-align:center;">
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
                <th colspan="3" style="text-align:left;">Procesos Funcionales</th>
                <th colspan="2" style="text-align:center;">Aprobado</th>
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
                <td width="35%"><?= end($obj_pruebas)->usuarioPruebas->nombreCompleto ?></td>
                <td class="td-placeholder" width="15%">
                    Firma
                </td>
                <td width="35%"><?= $obj_requerimientos_implementacion->usuarioRecibe->nombreCompleto ?></td>
                <td width="15%">Firma</td>
            </tr>
        </tbody>
    </table> 
    <p>
        <b>Nivel de satisfaccion</b>(Siendo 1 No satisfecho y 5 muy satisfecho) |1|2|3|4|5|
    </p>
    <p>
        <b>Observaciones adicionales:</b>____________________________________________________________________________
        <br>
        __________________________________________________________________________________________________________
        <br>
        __________________________________________________________________________________________________________
    </p>
</div>
<div id="div-implementacion">
    
    <div id="div-soporte">
        
        <table border="1" style="width:100%">
            <thead>
                <tr>
                    <th colspan="3" style="text-align:center;">Implementación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="95%" colspan="2" style="text-align:center;">Actualizacion en Produccion</th>
                    <th width="5%" style="text-align:center;">Fecha de Entrega</th>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        Entregado Por:
                        <br>
                        <?= $obj_requerimientos_implementacion->soporteEntregadoPor->nombreCompleto ?>
                        <!--Francisco Andres Ortega Florez-->
                    </td>
                    <td width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        <?= $obj_requerimientos_implementacion->soporte_entregado_fecha ?>
                    </td>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        Recibio Capacitacion:
                        <br>
                        <?= $obj_requerimientos_implementacion->soporte1RecibioCapacitacion->nombreCompleto ?>
                    </td>
                    <td width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                       <?= $obj_requerimientos_implementacion->soporte1_fecha_entrega ?>
                    </td>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        <?= $obj_requerimientos_implementacion->soporte2RecibioCapacitacion->nombreCompleto ?>
                    </td>
                    <td width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        <?= $obj_requerimientos_implementacion->soporte2_fecha_entrega ?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        
        
    </div>
    <div id="div-produccion1">
        
        <table border="1" style="width:100%">
            <tbody>
                <tr>
                    <td width="50%" style="text-align:left;">Usuario Aprueba Actualizacion en Produccion</td>
                    <td width="50%" style="text-align:center;">Fecha sugerida Actualizacion</td>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        <?= $obj_requerimientos_implementacion->usuarioApruebaProduccion->nombreCompleto ?>
                    </td>
                    <td width="5%" style="text-align:center;">
                        <?= $obj_requerimientos_implementacion->fecha_subida_produccion ?>
                    </td>
                </tr>
            </tbody>
        </table>
        
    </div>
    <div id="div-produccion2">
        
        <table border="1" style="width:100%">
            <tbody>
                <tr>
                    <th width="95%" colspan="2" style="text-align:center;">Actualizacion en Produccion</th>
                    <th width="5%" style="text-align:center;">Fecha de Entrega</th>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        Entregado Por:
                        <br>
                        <?= $obj_requerimientos_implementacion->produccionEntregadoPor->nombreCompleto ?>
                    </td>
                    <td width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        <?= $obj_requerimientos_implementacion->produccion_entregado_fecha ?>
                    </td>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        <?= $obj_requerimientos_implementacion->produccion1RecibioCapacitacion->nombreCompleto ?>
                    </td>
                    <td width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        <?= $obj_requerimientos_implementacion->produccion1_fecha_entrega ?>
                    </td>
                </tr>
                <tr>
                    <td width="90" style="text-align:left;">
                        <?= empty($obj_requerimientos_implementacion->produccion2RecibioCapacitacion) ? '(vacio)' : $obj_requerimientos_implementacion->produccion2RecibioCapacitacion->nombreCompleto  ?>
                    </td>
                    <td width="5%">Firma</td>
                    <td width="5%" style="text-align:center;">
                        <?= empty($obj_requerimientos_implementacion->produccion2_fecha_entrega) ? '(vacio)' : $obj_requerimientos_implementacion->produccion2_fecha_entrega  ?>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>