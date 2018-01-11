<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm; 

/* @var $this yii\web\View */ 
/* @var $model app\models\ProcesosInvolucrados */ 
/* @var $form yii\widgets\ActiveForm */ 
?> 


    <?php $form = ActiveForm::begin([
          'id' => 'procesos_involucrados-form',
          'enableAjaxValidation' => true,
          'enableClientScript' => true,
          'enableClientValidation' => true,
        ]); 
    ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>


    <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
    </div> 

    <?php ActiveForm::end(); ?> 
 
<?php
    $this->registerJs('
        $("form#procesos_involucrados-form").on("beforeSubmit", function(e) {

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
                $.pjax.reload({container:"#grid_procesos_involucrados"});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>