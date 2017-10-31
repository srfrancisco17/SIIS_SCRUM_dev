<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TiposDocumentos */
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
    'id' => 'TipoDocumentos-form',
      'enableAjaxValidation' => true,
      'enableClientScript' => true,
      'enableClientValidation' => true,
    ]); 
?>
<div class="row">
    <div class="col-lg-4">
        <?= $form->field($model, 'documento_id')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'orden')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <?= Html::submitButton($model->isNewRecord ? 'Crear Tipo Documento' : 'Actualizar Tipo Documento', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
    $this->registerJs('
        $("form#TipoDocumentos-form").on("beforeSubmit", function(e) {
            var form = $(this);
            $.post(
                form.attr("action")+"&submit=true",
                form.serialize()
            )
            .done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:"#TipoDocumentos-grid"});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>