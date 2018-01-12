<?php

namespace app\models;

use Yii;
use app\models\Sprints;
use app\models\SprintRequerimientosTareas;

class HelpersFAOF
{

    public function actualizarTiempos($connection, $sprint_id, $requerimiento_id){
        
        $total_horas_requerimiento = $connection->createCommand("SELECT SUM(horas_desarrollo) FROM requerimientos_tareas WHERE requerimiento_id = ".$requerimiento_id)->queryScalar();
        
        

        /*
         * requerimientos - UPDATE
         */
        
        $connection->createCommand()->update('requerimientos', ['tiempo_desarrollo' => $total_horas_requerimiento])->execute();
        
        
        if ( !empty($sprint_id) ){
  
            $update_sprint_requerimientos = "
                UPDATE sprint_requerimientos
                SET tiempo_desarrollo = subquery.total_horas
                FROM (
                                SELECT
                                        SUM(RT.horas_desarrollo) AS total_horas
                                FROM
                                        requerimientos_tareas AS RT

                                INNER JOIN sprint_requerimientos_tareas AS SRT ON(
                                        RT.tarea_id = SRT.tarea_id 
                                        AND SRT.requerimiento_id = RT.requerimiento_id
                                )
                                WHERE SRT.sprint_id = ".$sprint_id." AND SRT.requerimiento_id = ".$requerimiento_id."
                      ) AS subquery
                WHERE sprint_id = ".$sprint_id." AND requerimiento_id = ".$requerimiento_id.";
            ";
            
            
            $update_sprints = " 
                
                UPDATE sprints
                    SET horas_desarrollo = subquery.total_horas
                FROM(
                    SELECT
                        SUM(tiempo_desarrollo) AS total_horas
                    FROM
                        sprint_requerimientos
                    WHERE
                        sprint_id = ".$sprint_id."
                    ) AS subquery
                WHERE sprint_id = ".$sprint_id.";
         
            ";
            
            /* Actualizar tiempos SPRINT_REQUERIMIENTOS */
            $connection->createCommand($update_sprint_requerimientos)->execute();
            
            /* Actualizar tiempos SPRINTS */
            $connection->createCommand($update_sprints)->execute();
            
        }
        

        return true;
        
    }
    
    public static function modalFormularios($titulo_modal){
        
        
        Yii::$app->view->registerJs("

            $(document).on('click', '.link_modal', (function(b) {

                var opcion_modal = $(this).data('opcion');
                var titulo_modal = $(this).data('titulo');

                if (opcion_modal == '1'){

                    $(\"#titulo_modal\").text('Crear ".$titulo_modal."');

                }else{
                    $(\"#titulo_modal\").text('Actualizar ".$titulo_modal."');
                }

                $.get(
                    $(this).data('url'),
                    function (data) {
                        $('.modal-body').html(data);
                        $('#modal').modal();
                    }
                );
            }));
            
        ");

        \yii\bootstrap\Modal::begin([
            'id' => 'modal',
            'header' => '<h4 id="titulo_modal" class="modal-title"></h4>',
        ]);
        echo "<div class='well'></div>";
        \yii\bootstrap\Modal::end();        
  
    }     
 
}
