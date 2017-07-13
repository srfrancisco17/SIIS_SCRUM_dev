<?php

namespace app\controllers;
use Yii;



class PruebasController extends \yii\web\Controller
{
    public function actionIndex()
    {
        
        //$sprint_id = 1;
        if (is_null(Yii::$app->request->post('sprint_id'))){
            $sprint_id = 1;
        }else{
            $sprint_id = Yii::$app->request->post('sprint_id');
        }
        
        $usuario_id = Yii::$app->request->post('list');
        
        
        return $this->render('index', [
            'usuario_id' => $usuario_id,
            'sprint_id' => $sprint_id
        ]);
    }

}
