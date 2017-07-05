<?php

namespace app\models;

use app\models\SprintRequerimientosTareas;
use app\models\SprintRequerimientos;

class EliminacionTareas{
    
    public static function estadoEspera($sprint_id, $requerimiento_id)
    {
        
        $query = SprintRequerimientosTareas::find()->where(['between', 'estado','2', '3'])->andWhere(['sprint_id' => $sprint_id])->andWhere(['requerimiento_id' => $requerimiento_id])->count();//
        
        $tareas_terminadas = SprintRequerimientosTareas::find()->where(['estado' => '4'])->andWhere(['sprint_id' => $sprint_id])->andWhere(['requerimiento_id' => $requerimiento_id])->count();
        
        if ($query == 0){
            
            if ($tareas_terminadas > 0){
                SprintRequerimientos::actualizarEstadoSprintRequerimientos($sprint_id, $requerimiento_id, '4');
            }
            
        }
        
    }
    
    public static function estadoProgreso($sprint_id, $requerimiento_id){
        
        $query = SprintRequerimientosTareas::find()->where(['between', 'estado','3', '4'])->andWhere(['sprint_id' => $sprint_id])->andWhere(['requerimiento_id' => $requerimiento_id])->count();//
        
        $tareas_terminadas = SprintRequerimientosTareas::find()->where(['estado' => '2'])->andWhere(['sprint_id' => $sprint_id])->andWhere(['requerimiento_id' => $requerimiento_id])->count();
        
        if ($query == 0){
            
            if ($tareas_terminadas > 0){
                SprintRequerimientos::actualizarEstadoSprintRequerimientos($sprint_id, $requerimiento_id, '2');
            }
            
        }
 
    }
}
    
?>

