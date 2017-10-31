<?php

namespace app\models;

use Yii;
use app\models\Sprints;

class ValorHelpers
{

    public function actualizarTiempos($sprint_id){
        
        $conexion = Yii::$app->db;
        
        $tiempo_desarrollo = $conexion->createCommand('
        select
            sum(r.tiempo_desarrollo) as sum_horas
            from sprint_requerimientos as sr
            left join requerimientos as r
            on (
                r.requerimiento_id = sr.requerimiento_id
            )      
            where sr.sprint_id = :sprint_id
        ')
        ->bindValue(':sprint_id', $sprint_id)      
        ->queryScalar();
                    
        Sprints::actualizarHorasSprints($sprint_id, $tiempo_desarrollo);
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
