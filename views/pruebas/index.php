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

/*
echo '<pre>';
print_r($array);
echo '</pre>';
*/
?>

<?php Pjax::begin(); ?>
<div class="row">
    
    <?= Html::beginForm(['pruebas/index'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>

    <div class="col-lg-2">
        <?= Select2::widget([
            'name' => 'sprint_id',
            'size' => Select2::SMALL,
            'value' => Yii::$app->request->post('sprint_id'), // value to initialize
            'data' => $arraySprints
        ])?>
    </div>
    <div class="col-lg-2">
        <?= Select2::widget([
            'name' => 'list',
            'size' => Select2::SMALL,
            'value' => Yii::$app->request->post('list'),
            'options' => ['placeholder'=>'Todos Los Usuarios'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'data' => \app\models\SprintUsuarios::getListaDesarrolladores(1)
        ])?>

    </div>
    <div class="col-lg-2">
            <?= Html::submitButton('Cargar', ['class' => 'btn btn-sm btn-primary', 'name' => 'hash-button']) ?>
    </div>
    <?= Html::endForm() ?>
</div>


<h3><?= $sprint_id ?></h3>
<h3><?= $usuario_id ?></h3>

<?php Pjax::end(); ?>