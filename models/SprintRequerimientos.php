<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
//use backend\models\SprintUsuarios;
use app\models\Usuarios;
/** 
 * This is the model class for table "sprint_requerimientos". 
 * 
 * @property int $sprint_id
 * @property int $requerimiento_id
 * @property int $usuario_asignado
 * @property int $tiempo_desarrollo
 * @property string $estado
 * 
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
            [['sprint_id', 'requerimiento_id', 'estado'], 'required'],
            [['sprint_id', 'requerimiento_id', 'usuario_asignado', 'tiempo_desarrollo'], 'integer'],
            [['estado'], 'string', 'max' => 2],
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
        
        $sql = 'Select * From requerimientos where Not requerimiento_id In (Select requerimiento_id From sprint_requerimientos)';
        $query = SprintRequerimientos::findBySql($sql)->asArray()->all();
        //$query = Requerimientos::find()->where(['estado'=>'1'])->all();
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
    
}
