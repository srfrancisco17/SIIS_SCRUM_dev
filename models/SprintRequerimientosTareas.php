<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sprint_requerimientos_tareas".
 *
 * @property integer $tarea_id
 * @property integer $sprint_id
 * @property integer $requerimiento_id
 * @property string $estado
 *
 * @property EstadosReqSpr $estado0
 * @property Requerimientos $requerimiento
 * @property RequerimientosTareas $tarea
 * @property Sprints $sprint
 */
class SprintRequerimientosTareas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sprint_requerimientos_tareas';
    }
    
    public static function primaryKey()
    {
        return ['tarea_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'requerimiento_id'], 'integer'],
            [['sprint_id'], 'default', 'value' => null],
            [['requerimiento_id'], 'required'],
            [['estado'], 'string', 'max' => 2],
            [['estado'], 'default', 'value' => '2'],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['estado' => 'req_spr_id']],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
            [['tarea_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequerimientosTareas::className(), 'targetAttribute' => ['tarea_id' => 'tarea_id']],
            [['sprint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sprints::className(), 'targetAttribute' => ['sprint_id' => 'sprint_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarea_id' => 'Tarea ID',
            'sprint_id' => 'Sprint ID',
            'requerimiento_id' => 'Requerimiento ID',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(EstadosReqSpr::className(), ['req_spr_id' => 'estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimiento()
    {
        return $this->hasOne(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarea()
    {
        return $this->hasOne(RequerimientosTareas::className(), ['tarea_id' => 'tarea_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprint()
    {
        return $this->hasOne(Sprints::className(), ['sprint_id' => 'sprint_id']);
    }
    
    public function actualizarEstadoTareas($id, $estado, $sw_control = null){
        
        $conexion = Yii::$app->db;
        
        
        if ($sw_control == 4){
            
            $expression = new \yii\db\Expression('NOW()');
            $now = (new \yii\db\Query)->select('now()::timestamp')->scalar();
            
            $conexion->createCommand()->update('sprint_requerimientos_tareas', [
                'estado' => $estado,
            ],  'tarea_id ='.$id)->execute();      
            
            /*
             * NUEVO 19/10/2017
             * Cambio actualizar el ultimo estado a terminado
             * 
             */
            $conexion->createCommand()->update('requerimientos_tareas', [
                'fecha_terminado' => $now,
                'ultimo_estado' => '4',
            ],  'tarea_id ='.$id)->execute(); 
            
        }else{
            
            $conexion->createCommand()->update('sprint_requerimientos_tareas', [
                'estado' => $estado,
            ],  'tarea_id ='.$id)->execute(); 
            
            /*
             * NUEVO 19/10/2017
             * Cambio para cambiar el ultimo_estado y fecha_terminado a NULL
             * 
             */
            
            $conexion->createCommand()->update('requerimientos_tareas', [
                'fecha_terminado' => NULL,
                'ultimo_estado' => NULL,
            ],  'tarea_id ='.$id)->execute(); 

        }     

        return true;  
        
    }
    
    
}
