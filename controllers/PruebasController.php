<?php

namespace app\controllers;
use Yii;



class PruebasController extends \yii\web\Controller
{

    
    public function actionIndex()
    {


        
        return $this->render('index', [
//            'usuario_id' => $usuario_id,

        ]);
    }
    
    public function actionInsertar(){
        
    
    /*
        $number = count($_POST['name']);
        
        //echo $number.'<br>';
        echo '<pre>';
        var_dump($_POST['name']);
        echo '</pre>';
        if ($number > 0){
            $conexion = Yii::$app->db;
            
            for ($i=0; $i < $number; $i++){
                
                if (!empty($_POST['name'][$i])){
                    
                $sql = "INSERT INTO comites (comite_alias, lugar) VALUES ('".$_POST['name'][$i]."', '".$_POST['lugar'][$i]."' )";

                 $conexion->createCommand($sql)->execute();

                 echo 'Datos Insertados';
                    
                }
                        
                

            }
            
        }else{
            echo 'enter name';
        }
        
     */return $this->redirect('index');  

        if (Yii::$app->request->post()){
            
            echo 'ESTOY EN EL POST';
            
        }else{
            return $this->redirect('index');  
        }
    }

}
