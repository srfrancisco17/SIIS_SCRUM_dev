<?php

namespace app\controllers;
use app\models\SprintRequerimientos;
use app\models\SprintUsuarios;
use yii;

class DiagramasController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionGantt()
    {
        //$sprint_requerimientos = SprintRequerimientos::find()->where(['sprint_id' => 1])->orderBy(['usuario_asignado'=>SORT_DESC, 'prioridad' => SORT_ASC])->with('requerimiento')->all();
        
        //$consulta = \app\models\Sprints::find()->where(['sprint_id' => 1])->with('sprintRequerimientos')->orderBy(['usuario_asignado' => SORT_DESC])->all();
        
        //$consulta = \app\models\Sprints::find()->where(['sprints.sprint_id' => 1])->joinWith('sprintRequerimientos')->orderBy(['usuario_asignado' => SORT_DESC])->all();
        
        $consulta = SprintUsuarios::find()->where(['sprint_usuarios.sprint_id' => 1])->joinWith('sprintRequerimientos')->orderBy(['usuario_id' => SORT_DESC])->all();
        
        return $this->render('gantt',[
            //'sprint_requerimientos'=>$sprint_requerimientos
            'consulta'=>$consulta
        ]);
    }
    
    public function actionBurndown2(){
    
    //$consulta_burndown = SprintUsuarios::findOne(['sprint_id' => '1', 'usuario_id' => Yii::$app->user->identity->usuario_id]);
    
    $consulta_ideal_burn = SprintRequerimientos::findOne(['sprint_id' => '1', 'usuario_asignado' => Yii::$app->user->identity->usuario_id]);
    $consulta_tiempo_desarrollo = SprintRequerimientos::find()->where(['sprint_id' => '1'])->andWhere(['usuario_asignado' => Yii::$app->user->identity->usuario_id])->sum('tiempo_desarrollo');
    
    
    $connection = Yii::$app->db;
    $consulta_acutal_burn = $connection->createCommand("select 
                                        sum(srt.tiempo_desarrollo) as sum_horas,
                                        srt.fecha_terminado::date
                                        from sprint_requerimientos as sr
                                        inner join sprint_requerimientos_tareas as srt
                                        on (
                                                srt.requerimiento_id = sr.requerimiento_id
                                                and srt.sprint_id = sr.sprint_id
                                        )
                                        where sr.sprint_id = :sprint_id
                                        and sr.usuario_asignado = :usuario_asignado
                                        and srt.estado = '4'
                                        group by srt.fecha_terminado::date
                                        order by srt.fecha_terminado::date")
                                        ->bindValue(':sprint_id', 1)
                                        ->bindValue(':usuario_asignado', Yii::$app->user->identity->usuario_id)
                                        ->queryAll();
     
    
        return $this->render('burndown',[
            'consulta_ideal_burn'=>$consulta_ideal_burn,
            'consulta_tiempo_desarrollo' => $consulta_tiempo_desarrollo,
            'consulta_acutal_burn' => $consulta_acutal_burn
        ]);
        
    }

}
