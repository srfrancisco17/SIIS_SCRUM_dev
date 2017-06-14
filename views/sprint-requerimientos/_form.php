<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;


/* @var $this yii\web\View */
/* @var $model app\models\SprintRequerimientos */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="sprint-requerimientos-form">
    <?php
        $form = ActiveForm::begin([
                'id' => 'sprintRequerimientos-form',
                'enableAjaxValidation' => true,
                'enableClientScript' => true,
                'enableClientValidation' => true,
        ]);
    ?>
    <?php
        if ($model->isNewRecord) {
    ?>
     <?= $form->field($model, 'sprint_id')->hiddenInput(['value'=> $sprint_id])->label(false); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'requerimiento_id')->dropDownList($model->ListaRequerimientos, ['prompt' => 'Seleccione Uno'])->label('(*) Requerimiento:'); ?>
        </div>
       <div class="col-lg-4">
           

           
            <?= $form->field($model, 'usuario_asignado')->dropDownList(yii\helpers\ArrayHelper::map(app\models\SprintUsuarios::find()->where(['sprint_id'=>$sprint_id])->andWhere(['estado'=>'1'])->all(), 'usuario_id', 'usuario.nombres'), ['prompt' => 'Seleccione Desarrollador']); ?>
        
       </div>
        <div class="col-lg-4">
            
            <?= $form->field($model, 'prioridad')->widget(DepDrop::classname(), [
                'options'=>['id'=>'prioridad-id'],
                'pluginOptions'=>[
                    'depends'=>[Html::getInputId($model, 'usuario_asignado')],
                    'placeholder'=>false,
                    'url'=>Url::to(['/sprint-requerimientos/subcat', 'sprint_id' => $sprint_id])
                ]
            ]) ?>
        </div>
    </div>
   <?php
            
        }else{
            
   ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'requerimiento_id')->textInput(['value'=> $model->requerimiento->requerimiento_titulo, 'disabled' => true])->label('(*) Requerimiento:');?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'usuario_asignado')->dropDownList(yii\helpers\ArrayHelper::map(app\models\SprintUsuarios::find()->where(['sprint_id'=>$model->sprint_id])->andWhere(['estado'=>'1'])->all(), 'usuario_id', 'usuario.nombres'), ['prompt' => 'Seleccione Usuario' ])->label('Desarrollador:'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'tiempo_desarrollo')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'estado')->dropDownList(ArrayHelper::map(\app\models\EstadosReqSpr::find()->where(['sw_sprint_req'=>'1'])->asArray()->all(), 'req_spr_id', 'descripcion'), ['prompt' => 'Seleccione Uno', 'disabled' => true])->label('(*) Estado'); ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'prioridad')->dropDownList(yii\helpers\ArrayHelper::map(app\models\PrioridadSprintRequerimientos::find()->all(), 'prioridad_id', 'descripcion'), ['prompt' => 'Seleccione Prioridad', 'disabled' => true])->label('Prioridad:'); ?>
        </div> 
    </div>
    <?php    
        }
    ?>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <?= Html::submitButton($model->isNewRecord ? 'Agregar RQM' : 'Actualizar RQM', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
    <?php
        $this->registerJs('
        // obtener la id del formulario y establecer el manejador de eventos
            $("form#sprintRequerimientos-form").on("beforeSubmit", function(e) {
                var form = $(this);
                $.post(
                    form.attr("action")+"&submit=true",
                    form.serialize()
                )
                .done(function(result) {
                    form.parent().html(result.message);
                    $.pjax.reload({container:"#sprintRequerimiento-grid"});
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
