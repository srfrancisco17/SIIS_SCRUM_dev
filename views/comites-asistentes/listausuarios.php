<?php
/* @var $this yii\web\View */
//use backend\models\UsuariosSearch;
use kartik\grid\GridView;

$this->title = false;
?>
<?php
//print_r($dataProvider->getModels());
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider2,
    'filterModel' => $searchModel2,
    'columns' => [
        'usuario_id',
        'nombres',
        'apellidos',
        // ...
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS
        ],
    ],
    'pjax' => true,
]) ?>

<input type="submit"  class="btn btn-success" name="enviar_datos" id="enviar_datos" value="Agregar Usuario"> 
<button onclick="myFunction()" value="Datos"/>
<!--<button onclick="myFunction()" value="ObtenerDatos">Obtener Id Seleccionados</button>-->

<?php    
    $this->registerJs("
        function myFunction() {
            var keys = $('#kv-grid-listausuarios').yiiGridView('getSelectedRows');
            alert(keys);    
        }   

        $('#enviar_datos').click(function(e){

            var form = $('#comite-asistente-form');
            var keys = $('#kv-grid-listausuarios').yiiGridView('getSelectedRows');
            $.post(
                form.action = 'index.php?r=comites-asistentes/respuesta&id='+$('#comitesasistentes-comite_id').val()+'&k='+keys,
                form.serialize()
            ).done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:'#grid_comites-asistentes'});

            });

            e.preventDefault();    
            return false;
        });
    ");
?>