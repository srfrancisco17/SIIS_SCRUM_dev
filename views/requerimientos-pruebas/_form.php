<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
?>
<?php $form = ActiveForm::begin([
      'id' => 'requerimientos_pruebas-form',
      'enableAjaxValidation' => true,
      'enableClientScript' => true,
      'enableClientValidation' => true,
    ]); 
?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'fecha_entrega')->widget(DatePicker::classname(), [
            //'name' => 'dp_3',
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
            //'name' => 'dp_3',
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
        <?= $form->field($model, 'estado')->dropDownList(
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
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
    $this->registerJs('
        $("form#requerimientos_pruebas-form").on("beforeSubmit", function(e) {

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
                $.pjax.reload({container:"#grid-requerimientos_pruebas"});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>