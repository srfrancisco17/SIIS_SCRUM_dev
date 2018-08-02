<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$form = ActiveForm::begin([
      'id' => 'requerimientos_pruebas-form',
      'enableAjaxValidation' => true,
      'enableClientScript' => true,
      'enableClientValidation' => true,
]); 

$model->fecha_prueba = ($model->isNewRecord ? date("Y-m-d") : $model->fecha_prueba);

?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'fecha_entrega')->widget(DatePicker::classname(), [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'size' => 'xs',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd',
                'startDate' => '2018-01-01'
            ]
            ])->label('* Fecha de entrega:'); 
        ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'fecha_prueba')->widget(DatePicker::classname(), [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'size' => 'xs',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd',
                'startDate' => '2018-01-01'
            ]
            ])->label('* Fecha de prueba:'); 
        ?>
    </div>
    <div class="col-md-4">
        
        <?= $form->field($model, 'estado', ['enableAjaxValidation' => false,  'enableClientValidation' => false])->dropDownList(
                ['1' => 'Aprobado', '0' => 'No Aprobado'], ['prompt' => 'Seleccione Estado']
        )->label('(*) Estado:')
        ?>
    </div>    
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'observaciones')->textarea(['rows' => '3'])->label("Observaciones:") ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead style="background-color: #a50048; color: #FEFEFE;">
                <tr>
                    <th style="text-align: center; border: 1px solid #b9b4b4;" width="2%">#</th>
                    <th style='border: 1px solid #b9b4b4;' width="27%">TAREA TITULO</th>
                    <th style='border: 1px solid #b9b4b4;' width="51%">TAREA DESCRIPCIÓN</th>
                    <th style='border: 1px solid #b9b4b4;'>HORAS</th>
                    <th style='border: 1px solid #b9b4b4;'>APROBAR</th>
                    <th style='border: 1px solid #b9b4b4;'>NO APROBAR</th>
                </tr>
            </thead>
            <tbody>
            <?php

            $html = "";
            $count = 0;

            if(!empty($obj_tareas)){

                foreach ($obj_tareas as $key => $value) {

                    
                $tareas_pruebas_id = (empty($value->id) ? "NULL": $value->id);    
                    
                $html .= "<tr>";  
                $html .= "  <td style='text-align: center; border: 1px solid #b9b4b4;'>".($count+1)."</td>";
                $html .= "  <td style='border: 1px solid #b9b4b4;'>".$value->tarea->tarea_titulo."</td>";
                $html .= "  <td style='border: 1px solid #b9b4b4;'>".$value->tarea->tarea_descripcion."</td>";
                $html .= "  <td style='text-align: center; border: 1px solid #b9b4b4;'>".$value->tarea->horas_desarrollo."</td>";
                
                $checked_aprobado = "";
                $checked_Noaprobado = "";
                
                if ( !is_null($value->estado) ){
                    
                    //$html .= $value->estado;
                    
                    if ($value->estado == '1'){
                        $checked_aprobado = "checked";
                    }else if ($value->estado == '0'){
                        $checked_Noaprobado = "checked";
                    }
    
                }
  
                $html .= "
                    <td style='text-align:center; border: 1px solid #b9b4b4;'>
                        <label><input style='transform: scale(1.8); cursor: pointer;' type='radio' name='radio_tareas[".$count."]' value='".$value->tarea->tarea_id."-1-".$tareas_pruebas_id."' ".$checked_aprobado."></label>
                    </td>
                ";
                $html .= "
                    <td style='text-align:center; border: 1px solid #b9b4b4;'>
                        <label><input style='transform: scale(1.8); cursor: pointer;' type='radio' name='radio_tareas[".$count."]' value='".$value->tarea->tarea_id."-0-".$tareas_pruebas_id."' ".$checked_Noaprobado."></label>
                    </td>
                ";
                
                $html .= "</tr>";  
                $count++;

                }
            
            }else{
                $html .= "<tr>";  
                $html .= "  <td colspan='6'>No se encotraron resultados</td>";
                $html .= "</tr>";  
            }

            echo $html;
            
            ?>
                 
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
    $this->registerJs('
        $("form#requerimientos_pruebas-form").on("beforeSubmit", function(e) {

            var estado_prueba = 1;
            var count_inputCheck = 0;

            $("input[name^=\"radio_tareas\"]").each(function(){
                if (this.checked) {
                
                    array_values = $(this).val().split("-");
                    
                    if (array_values[1] == 0){
                        estado_prueba = 0;
                    }
                    count_inputCheck++;
                }
            }); 

            $("#requerimientospruebas-estado").val(estado_prueba);
                        

            
            /*
            if (  ($("input[name^=\"radio_tareas\"]").length)/2 != count_inputCheck){
                
                alert("Es obligatorio evaluar TODAS las tareas");
                return false;

            }
            */
            
            var form = $(this);
            $.post(
                form.attr("action")+"&submit=true",
                form.serialize()
            )
            .done(function(result) {
                form.parent().html(result.message);
                setTimeout(function(){
                    $("#modal").modal("hide");
                    $(".modal-body").html("");
                    
                }, 1000);
                $.pjax.reload({container:"#grid-requerimientos_pruebas", timeout: false});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>