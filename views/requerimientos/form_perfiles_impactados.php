<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */ 
/* @var $model app\models\PerfilesUsuariosImpactados */ 
/* @var $form yii\widgets\ActiveForm */ 
?> 

<?php $form = ActiveForm::begin([
      'id' => 'perfiles_usuario-form',
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
        
        setTimeout(function(){ $("#'.Html::getInputId($model, 'descripcion').'").focus(); }, 275);

        $("form#perfiles_usuario-form").on("beforeSubmit", function(e) {

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
                $.pjax.reload({container:"#grid_perfiles_usuarios", timeout: false});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>