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
    
    $consulta_burndown = SprintRequerimientos::findOne(['sprint_id' => '1', 'usuario_asignado' => Yii::$app->user->identity->usuario_id]);
    $consulta_tiempo_desarrollo = SprintRequerimientos::find()->where(['sprint_id' => '1'])->andWhere(['usuario_asignado' => Yii::$app->user->identity->usuario_id])->sum('tiempo_desarrollo');
        
        return $this->render('burndown',[
            'consulta_burndown'=>$consulta_burndown,
            'consulta_tiempo_desarrollo' => $consulta_tiempo_desarrollo
        ]);
        
    }

}
