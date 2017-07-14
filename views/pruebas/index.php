<?php
/* @var $this yii\web\View */

use app\models\Usuarios;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;



$array = array(
    '19' => 'Jorge Rodriguez',
    '20' => 'Harrison Gonzalez',
    '21' => 'Juan Pablo Sanchez Echavarria',
    '22' => 'Juan Fernando Hoyos',
    '23' => 'Leonardo Rodriguez',
    '24' => 'Luisa Fernanda Bedoya',
);

$arraySprints = array(
    '1' => 'Sprint (1)',
    '2' => 'Sprint (2)',
);





?>


<div class="row">
    
    <div class="col-lg-12">
        
        <?php
        
        $contrasena = '$2y$13$.GZ4NIiMoeNTe8TBkv1evOMJGp0vACf4VQTHd97pMyXxpDPLfD5Ja';
            
        
        //var_dump($operacion);
        echo '<br>';

        
        if (Yii::$app->getSecurity()->validatePassword('1234567', $contrasena)) {
            // all good, logging user in
            echo 'se completo exitosamente';
        } else {
            // wrong password
            echo 'ERROR';
        }
        
        
        ?>
       
    </div>

</div>
