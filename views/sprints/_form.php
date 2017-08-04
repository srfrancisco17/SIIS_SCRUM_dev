<?php

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use kartik\field\FieldRange;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\Sprints */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprints-form">
    <?php $form = ActiveForm::begin([
          'id' => 'sprints-form',
          'enableAjaxValidation' => true,
          'enableClientScript' => true,
          'enableClientValidation' => true,
        ]); 
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'sprint_alias')->textInput()->label('(*) Sprint Alias')?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'horas_desarrollo')->textInput(['type' => 'number', 'disabled' => true])?>
        </div>
        <div class="col-lg-3">
        <?php 
            if($model->isNewRecord){

                $model->estado  = 1;
                echo  $form->field($model, 'estado')->dropDownList(ArrayHelper::map(\app\models\EstadosReqSpr::find()->where(['sw_sprint'=>'1'])->asArray()->all(), 'req_spr_id', 'descripcion'), ['prompt' => 'Seleccione Uno' ])->label('Estado');

            }else{
                echo  $form->field($model, 'estado')->dropDownList(ArrayHelper::map(\app\models\EstadosReqSpr::find()->where(['sw_sprint'=>'1'])->asArray()->all(), 'req_spr_id', 'descripcion'), ['prompt' => 'Seleccione Uno', 'disabled' => false])->label('Estado');
            }    
        ?>   
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
                echo FieldRange::widget([
                'form' => $form,
                'model' => $model,
                'label' => '(*) Fecha Inicio - Fecha Final',
                'attribute1' => 'fecha_desde',
                'attribute2' => 'fecha_hasta',
                'type' => FieldRange::INPUT_DATE,
                'separator' => '←hasta→',
                'widgetOptions1' => [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'php:yy-m-d',
                        'todayHighlight' => true
                    ]
                ],
                'widgetOptions2' => [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'php:yy-m-d',
                        'todayHighlight' => true
                    ]
                ],    
            ]);
    
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <br>
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <?= Html::submitButton($model->isNewRecord ? 'Crear Sprint' : 'Actualizar Sprint', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
    $this->registerJs('
    // obtener la id del formulario y establecer el manejador de eventos
        $("form#sprints-form").on("beforeSubmit", function(e) {
            var form = $(this);
            $.post(
                form.attr("action")+"&submit=true",
                form.serialize()
            )
            .done(function(result) {
                form.parent().html(result.message);
                $.pjax.reload({container:"#sprints-grid"});
            });
            return false;
        }).on("submit", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });
    ');
?>