<?php

namespace app\controllers;
use Yii;



class PruebasController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $conexion = Yii::$app->db;
        
        $barChart = $conexion->createCommand(" 

            select
                    usu.usuario_id, usu.nombres, usu.apellidos, sum(r.tiempo_desarrollo) as tiempo_total,
                    (
                            select
                                    sum(rt1.horas_desarrollo)
                            from sprint_requerimientos as sr1
                            left join sprint_requerimientos_tareas srt1
                            on(
                                    sr1.sprint_id = srt1.sprint_id
                                    and
                                    sr1.requerimiento_id = srt1.requerimiento_id
                            )
                            left join requerimientos_tareas as rt1
                            on(
                                    srt1.requerimiento_id = rt1.requerimiento_id
                                    and rt1.tarea_id = srt1.tarea_id
                            )
                            where sr1.sprint_id = 2 and srt1.estado = '4' and sr1.usuario_asignado = usu.usuario_id
                            group by sr1.usuario_asignado
                    ) as tiempo_terminado
            from
                    sprint_requerimientos as sr
            inner join requerimientos as r
            on (
                    r.requerimiento_id = sr.requerimiento_id
            )
            inner join usuarios as usu
            on (
                    usu.usuario_id = sr.usuario_asignado
            )
            where
                    sr.sprint_id = 2
            group by 1, 2, 3, sr.usuario_asignado
        
        ")->queryAll();
        
        
        return $this->render('index', [
//            'usuario_id' => $usuario_id,
            'resulado' => $resulado
        ]);
    }

}
