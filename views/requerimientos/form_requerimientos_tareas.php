<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequerimientosTareas */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord){
    $model->horas_desarrollo = 0;
}

?>

<?php

$form = ActiveForm::begin([
    'id' => 'tareas-form',
    'enableAjaxValidation' => true,
    'enableClientScript' => true,
    'enableClientValidation' => true,
]); 
?>

<div class="row">
    <div class="col-lg-9">
        <?= $form->field($model, 'tarea_titulo')->textInput(['maxlength' => true])->label('(*) Titulo') ?>
    </div>
    <div class="col-lg-3">
            <?= $form->field($model, 'horas_desarrollo')->textInput(['type' => 'number'])->label('Horas') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?= $form->field($model, 'tarea_descripcion')->textarea(['rows' => 6])->label('Descripcion') ?>  
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
    $this->registerJs('
        $("form#tareas-form").on("beforeSubmit", function(e) {

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
                $.pjax.reload({container:"#grid_tareas"});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>
