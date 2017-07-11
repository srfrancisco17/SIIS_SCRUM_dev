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
}

