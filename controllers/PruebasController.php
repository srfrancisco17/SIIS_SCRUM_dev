<?php

namespace app\controllers;
use Yii;



class PruebasController extends \yii\web\Controller
{
    public function actionIndex()
    {

        
        
        return $this->render('index', [
//            'usuario_id' => $usuario_id,
//            'sprint_id' => $sprint_id
        ]);
    }

}
