<?php

namespace app\models;

use Yii;

/** 
 * This is the model class for table "requerimientos". 
 * 
 * @property integer $requerimiento_id
 * @property integer $comite_id
 * @property string $requerimiento_titulo
 * @property string $requerimiento_descripcion
 * @property string $requerimiento_justificacion
 * @property integer $usuario_solicita
 * @property string $departamento_solicita
 * @property string $observaciones
 * @property string $fecha_requerimiento
 * @property string $estado
 * @property integer $tiempo_desarrollo
 * @property string $sw_soporte
 * 
 * @property Comites $comite
 * @property Departamentos $departamentoSolicita
 * @property EstadosReqSpr $estado0
 * @property Usuarios $usuarioSolicita
 * @property RequerimientosTareas[] $requerimientosTareas
 * @property SprintRequerimientos[] $sprintRequerimientos
 * @property Sprints[] $sprints
 * @property SprintRequerimientosTareas[] $sprintRequerimientosTareas
 */ 
class Requerimientos extends \yii\db\ActiveRecord
{
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comite_id', 'usuario_solicita'], 'default', 'value' => null],
            [['tiempo_desarrollo'], 'default', 'value' => 0],            
            [['comite_id', 'usuario_solicita', 'tiempo_desarrollo'], 'integer'],
            [['requerimiento_titulo', 'usuario_solicita', 'fecha_requerimiento', 'estado'], 'required'],
            [['requerimiento_descripcion', 'requerimiento_justificacion', 'observaciones'], 'string'],
            [['fecha_requerimiento'], 'safe'],
            [['requerimiento_titulo'], 'string', 'max' => 60],
            [['departamento_solicita'], 'string', 'max' => 4],
            [['departamento_solicita'], 'default', 'value' => NULL],  
            [['estado'], 'string', 'max' => 2],
            [['estado'], 'default', 'value' => '0'],
            [['sw_soporte'], 'string', 'max' => 1],
            [['comite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comites::className(), 'targetAttribute' => ['comite_id' => 'comite_id']],
            //[['departamento_solicita'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['departamento_solicita' => 'departamento_id']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['estado' => 'req_spr_id']],
            [['usuario_solicita'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_solicita' => 'usuario_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() 
    { 
        return [ 
            'requerimiento_id' => 'Requerimiento ID',
            'comite_id' => 'Comite ID',
            'requerimiento_titulo' => 'Titulo',
            'requerimiento_descripcion' => 'Descripcion',
            'requerimiento_justificacion' => 'Justificacion',
            'usuario_solicita' => 'Usuario Solicita',
            'departamento_solicita' => 'Departamento Solicita',
            'observaciones' => 'Observaciones',
            'fecha_requerimiento' => 'Fecha Requerimiento',
            'estado' => 'Estado',
            'tiempo_desarrollo' => 'Tiempo Desarrollo',
            'sw_soporte' => 'Sw Soporte',
        ]; 
    } 
    
    public function getRequerimientosTareas() 
    { 
        return $this->hasMany(RequerimientosTareas::className(), ['requerimiento_id' => 'requerimiento_id']);
    } 
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComite()
    {
        return $this->hasOne(Comites::className(), ['comite_id' => 'comite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamentoSolicita()
    {
        return $this->hasOne(Departamentos::className(), ['departamento_id' => 'departamento_solicita']);
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
    public function getUsuarioSolicita()
    {
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_solicita']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
    
    public function getSprintRequerimientos2()
    {
        return $this->hasOne(SprintRequerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprints()
    {
        return $this->hasMany(Sprints::className(), ['sprint_id' => 'sprint_id'])->viaTable('sprint_requerimientos', ['requerimiento_id' => 'requerimiento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientosTareas()
    {
        return $this->hasMany(SprintRequerimientosTareas::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
    
    public function actualizarEstadoRequerimientos($requerimiento_id, $estado){
        
        $conexion = Yii::$app->db;
           
            $conexion->createCommand()->update('requerimientos', [
                'estado' => $estado,
            ],  'requerimiento_id ='.$requerimiento_id)->execute();         
        
        return true;  
 
    }
    
    public function updateTiempoDesarrollo($requerimiento_id){
        
        $conexion = Yii::$app->db;
        
        
        $total_tareas = SprintRequerimientosTareas::find()->select('horas_desarrollo')->where(['sprint_requerimientos_tareas.requerimiento_id'=>$requerimiento_id])->joinWith('tarea')->sum('horas_desarrollo'); 
        
        
        $conexion ->createCommand()
            ->update('requerimientos', ['tiempo_desarrollo' => $total_tareas], 'requerimiento_id = :requerimiento_id')
            ->bindParam(':requerimiento_id', $requerimiento_id)
            ->execute();
        
        return TRUE;
    }
    
    
    public function createRequerimientosSoporte(){
        
        //Consulta para ver si a tiene un requerimiento de soporte
        
        
        
    }
 
}
