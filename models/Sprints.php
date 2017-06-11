<?php

namespace app\models;

use Yii;

/** 
 * This is the model class for table "sprints". 
 * 
 * @property int $sprint_id
 * @property string $sprint_alias
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property int $horas_desarrollo
 * @property string $observaciones
 * @property string $estado
 * 
 * @property SprintRequerimientos[] $sprintRequerimientos
 * @property Requerimientos[] $requerimientos
 * @property SprintRequerimientosTareas[] $sprintRequerimientosTareas
 * @property SprintUsuarios[] $sprintUsuarios
 * @property Usuarios[] $usuarios
 * @property EstadosReqSpr $estado0
 */  
class Sprints extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sprints';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        /*
         * El sprint_alias es obligatorio. pero en la tabla puede ir vacio/nulo
         */
        return [
            [['sprint_alias','fecha_desde', 'fecha_hasta', 'estado'], 'required'],
            [['fecha_desde', 'fecha_hasta'], 'safe'],
            [['horas_desarrollo'], 'default', 'value' => null],
            [['horas_desarrollo'], 'integer'],
            [['observaciones'], 'string'],
            [['sprint_alias'], 'string', 'max' => 60],
            [['estado'], 'string', 'max' => 2],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['estado' => 'req_spr_id']],
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels() 
    { 
        return [ 
            'sprint_id' => 'Sprint ID',
            'sprint_alias' => 'Sprint Alias',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'horas_desarrollo' => 'Horas Desarrollo',
            'observaciones' => 'Observaciones',
            'estado' => 'Estado',
        ]; 
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id'])->viaTable('sprint_requerimientos', ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientosTareas()
    {
        return $this->hasMany(SprintRequerimientosTareas::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintUsuarios()
    {
        return $this->hasMany(SprintUsuarios::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['usuario_id' => 'usuario_id'])->viaTable('sprint_usuarios', ['sprint_id' => 'sprint_id']);
    }
    
    /** 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getEstado0() 
    { 
        return $this->hasOne(EstadosReqSpr::className(), ['req_spr_id' => 'estado']);
    } 
    
    
    public function actualizarHorasSprints($sprint_id, $horas){

        $conexion = Yii::$app->db;

        $conexion->createCommand("UPDATE sprints SET horas_desarrollo=:horas WHERE sprint_id=:sprint_id")
        ->bindValue(':horas', $horas)
        ->bindValue(':sprint_id', $sprint_id)   
        ->execute();
        
    }
}
