<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
//use backend\models\SprintUsuarios;
use app\models\Usuarios;
use app\models\Sprints;
/** 
 * This is the model class for table "sprint_requerimientos". 
 * 
 * @property int $sprint_id
 * @property int $requerimiento_id
 * @property int $usuario_asignado
 * @property int $tiempo_desarrollo
 * @property string $estado
 * @property int $prioridad
 * 
 * @property PrioridadSprintRequerimientos $prioridad0
 * @property EstadosReqSpr $estado0
 * @property Requerimientos $requerimiento
 * @property SprintUsuarios $sprint
 * @property Sprints $sprint0
 */ 
class SprintRequerimientos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public $arreglo = array();
    
    public static function tableName()
    {
        return 'sprint_requerimientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'requerimiento_id'], 'required'],
            [['prioridad'], 'default', 'value' => null],
            [['sprint_id', 'requerimiento_id', 'usuario_asignado', 'tiempo_desarrollo'], 'integer'],
            [['prioridad'], 'integer', 'message' => 'No existen mas prioridades'],
            [['estado'], 'string', 'max' => 2],
            [['estado'], 'default', 'value' => '2'],
            [['prioridad'], 'exist', 'skipOnError' => true, 'targetClass' => PrioridadSprintRequerimientos::className(), 'targetAttribute' => ['prioridad' => 'prioridad_id']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['estado' => 'req_spr_id']],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
            [['sprint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sprints::className(), 'targetAttribute' => ['sprint_id' => 'sprint_id']],
            [['usuario_asignado'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_asignado' => 'usuario_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sprint_id' => 'Sprint ID',
            'requerimiento_id' => 'Requerimiento ID',
            'usuario_asignado' => 'Usuario Asignado',
            'tiempo_desarrollo' => 'Tiempo Desarrollo',
            'estado' => 'Estado',
            'prioridad' => 'Prioridad',
        ];
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getPrioridad0() 
    { 
        return $this->hasOne(PrioridadSprintRequerimientos::className(), ['prioridad_id' => 'prioridad']);
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
    
    public function getRequerimiento2()
    {
        return $this->hasMany(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
    
    public function getSprintRequerimientosTareas()
    {
        return $this->hasMany(SprintRequerimientosTareas::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
     
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprint()
    {
        return $this->hasOne(Sprints::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioAsignado()
    {
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_asignado']);
    }
    
    public static function getListaRequerimientos() {
        
        $sql = "Select * From requerimientos where Not requerimiento_id In (Select requerimiento_id From sprint_requerimientos)and estado='1' ";
        $query = SprintRequerimientos::findBySql($sql)->asArray()->all();
        //$query = Requerimientos::find()->where(['estado'=>'1'])->all();
        return ArrayHelper::map($query, 'requerimiento_id', 'requerimiento_titulo');
    }
    
    public static function getListaRequerimientos2(){
        
        $query = Requerimientos::find()->all();
        
        return ArrayHelper::map($query, 'requerimiento_id', 'requerimiento_titulo');
    }
    
    public static function getListaSprintUsuarios() {
        /*
        $opciones = SprintUsuarios::find()
                ->innerJoinWith('usuario','usuarios.usuario_id = sprint_usuarios.usuario_id')
                ->asArray()
                ->all();
         */
        
        /*
        $opciones = Usuarios::find()->select(['usuarios.usuario_id','nombres'])
                ->innerJoinWith('sprintUsuarios',FALSE)
                ->all();
        */
        
        $opciones = SprintUsuarios::find()->andWhere(['sprint_id'=>41])->all();
        /*
        echo '<pre>';
        print_r($opciones);
        echo '</pre>'; 
        */
        return ArrayHelper::map($opciones, 'usuario_id', 'usuario.nombres');
    }
    
    public function actualizarEstadoSprintRequerimientos($sprint_id, $requerimiento_id, $estado){
        
        $conexion = Yii::$app->db;
        
        $conexion->createCommand("UPDATE sprint_requerimientos SET estado=:estado WHERE sprint_id=:sprint_id AND requerimiento_id=:requerimiento_id")
        ->bindValue(':estado', $estado)
        ->bindValue(':sprint_id', $sprint_id)
        ->bindValue(':requerimiento_id', $requerimiento_id)       
        ->execute();
        
        
        \app\models\Requerimientos::actualizarEstadoRequerimientos($requerimiento_id, $estado);
        
        return true;  
 
    }
    
    public function actualizarHorasSprintRequerimientos($sprint_id, $requerimiento_id, $horas){
        
        $conexion = Yii::$app->db;
        
        $conexion->createCommand("UPDATE sprint_requerimientos SET tiempo_desarrollo=:horas WHERE sprint_id=:sprint_id AND requerimiento_id=:requerimiento_id")
        ->bindValue(':horas', $horas)
        ->bindValue(':sprint_id', $sprint_id)
        ->bindValue(':requerimiento_id', $requerimiento_id)       
        ->execute();
        
        
        $total_sprint = SprintRequerimientos::find()->select('tiempo_desarrollo')->where(['sprint_id'=>$sprint_id])->sum('tiempo_desarrollo'); 
        
        Sprints::actualizarHorasSprints($sprint_id, $total_sprint);
        
        return true;  
 
    }
    
    public function actualizarNoCumplido($sprint_id){
        
        $conexion = Yii::$app->db;
        
        
        
        $conexion->createCommand("UPDATE requerimientos as re SET estado = '1' "
                . "FROM sprint_requerimientos as sr WHERE "
                . "sr.estado BETWEEN '2'"
                . "AND '3' AND sr.sprint_id =:sprint_id AND sr.requerimiento_id = re.requerimiento_id")
        ->bindValue(':sprint_id', $sprint_id)     
        ->execute();
        
        
        $conexion->createCommand("UPDATE sprint_requerimientos SET estado = '5' WHERE estado BETWEEN '2' AND '3' AND sprint_id=:sprint_id")
        ->bindValue(':sprint_id', $sprint_id)     
        ->execute();
        
        return true;
        
    }
    

}
