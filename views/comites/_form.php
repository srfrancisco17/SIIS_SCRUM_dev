<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Comites */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="comites-form">
    <?php $form = ActiveForm::begin([
        'id' => 'comites-form',
        'enableAjaxValidation' => true,
        'enableClientScript' => true,
        'enableClientValidation' => true,
        ]); 
    ?>
    <?php if($model->isNewRecord){
            $model->fecha = date('Y-m-d');
            $model->estado = 1;
          }else{

          }    
    ?>
    <div class="row">
        <div class="col-xs-6 col-lg-6">
            <?= $form->field($model, 'comite_alias')->textInput(['maxlength' => true])->label('(*) Comite Alias:') ?>
        </div>
        <div class="col-xs-6 col-lg-6">
            <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
                'name' => 'dp_3',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'options' => ['placeholder' => 'Fecha Comite ...'],
                'size' => 'xs',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-lg-6">
            <?= $form->field($model, 'hora_desde')->widget(TimePicker::classname(), []); ?>
        </div>
        <div class="col-xs-6 col-lg-6">
            <?= $form->field($model, 'hora_hasta')->widget(TimePicker::classname(), []); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-lg-6">
            <?= $form->field($model, 'lugar')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-6 col-lg-6">
            <?= $form->field($model, 'estado')->dropDownList(
                ['1' => 'Activo', '0' => 'Inactivo'],
                ['prompt'=>'Seleccione Estado']    
            ) ?>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <?= Html::submitButton($model->isNewRecord ? 'Crear Comite' : 'Actualizar Comite', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
        $this->registerJs('
        // obtener la id del formulario y establecer el manejador de eventos
            $("form#comites-form").on("beforeSubmit", function(e) {
                var form = $(this);
                $.post(
                    form.attr("action")+"&submit=true",
                    form.serialize()
                )
                .done(function(result) {
                    form.parent().html(result.message);
                    $.pjax.reload({container:"#comites-grid"});
                });
                return false;
            }).on("submit", function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            });
        ');
    ?>
</div>
