<?php

namespace app\controllers;
use app\models\SprintRequerimientos;

class DiagramasMasterController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionGantt()
    {
        $sprint_requerimientos = SprintRequerimientos::find()->where(['sprint_id' => 1])->orderBy(['usuario_asignado'=>SORT_DESC, 'prioridad' => SORT_DESC])->all();
        
        
        return $this->render('gantt',[
            'sprint_requerimientos'=>$sprint_requerimientos
        ]);
    }

}
