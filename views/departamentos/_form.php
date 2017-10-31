<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Departamentos */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord){

    $mensaje = "Crear tipos documentos";

}else{
    $mensaje = "Actualizar  tipos documentos";
}

$this->registerJs(
    "$(document).ready(function(){
        $(\"#titulo_modal\").text('".$mensaje."');
    });"
);

?>
<?php $form = ActiveForm::begin([
    'id' => 'Departamentos-form',
      'enableAjaxValidation' => true,
      'enableClientScript' => true,
      'enableClientValidation' => true,
    ]); 
?>
<?= $form->field($model, 'departamento_id')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    <?= Html::submitButton($model->isNewRecord ? 'Crear Departamentos' : 'Actualizar Departamentos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php
    $this->registerJs('
        $("form#Departamentos-form").on("beforeSubmit", function(e) {
            var form = $(this);
            $.post(
                form.attr("action")+"&submit=true",
                form.serialize()
            )
            .done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:"#Departamentos-grid"});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>
