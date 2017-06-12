<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sprint_requerimientos_tareas".
 *
 * @property int $tarea_id
 * @property int $sprint_id
 * @property int $requerimiento_id
 * @property string $tarea_titulo
 * @property string $tarea_descripcion
 * @property string $estado
 * @property int $tiempo_desarrollo
 *
 * @property Requerimientos $requerimiento
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'requerimiento_id', 'tiempo_desarrollo'], 'default', 'value' => null],
            [['sprint_id', 'requerimiento_id', 'tiempo_desarrollo'], 'integer'],
            //[['sprint_id', 'requerimiento_id'], 'required'],
            [['tiempo_desarrollo'], 'default', 'value' => 0],
            [['tarea_descripcion'], 'string'],
            [['tarea_titulo'], 'string', 'max' => 60],
            [['estado'], 'string', 'max' => 2],
            [['estado'], 'default', 'value' => '2'],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['estado' => 'req_spr_id']],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
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
            'tarea_titulo' => 'Tarea Titulo',
            'tarea_descripcion' => 'Tarea Descripcion',
            'estado' => 'Estado',
            'tiempo_desarrollo' => 'Tiempo Desarrollo',
        ];
    }
    
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
    public function getSprint()
    {
        return $this->hasOne(Sprints::className(), ['sprint_id' => 'sprint_id']);
    }
    
    public function actualizarEstadoTareas($id, $estado){
        
        $conexion = Yii::$app->db;
           
        //$usuarios = explode(",",$key);
            
            $conexion->createCommand()->update('sprint_requerimientos_tareas', [
                'estado' => $estado,
            ],  'tarea_id ='.$id)->execute();         
        
        return true;  
        
    }
}
