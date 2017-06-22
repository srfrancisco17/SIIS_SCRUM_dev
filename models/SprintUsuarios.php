<?php

namespace app\models;

use yii\db\Query;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "sprint_usuarios".
 *
 * @property int $sprint_id
 * @property int $usuario_id
 * @property int $horas_desarrollo
 * @property string $observacion
 * @property string $estado
 *
 * @property SprintRequerimientos[] $sprintRequerimientos
 * @property Sprints $sprint
 * @property Usuarios $usuario
 */
class SprintUsuarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public $estado1;
    
    public static function tableName()
    {
        return 'sprint_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'usuario_id'], 'required'],
            [['sprint_id', 'usuario_id', 'horas_desarrollo'], 'default', 'value' => null],
            [['sprint_id', 'usuario_id', 'horas_desarrollo'], 'integer'],
            [['observacion'], 'string'],
            [['estado'], 'string', 'max' => 1],
            [['sprint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sprints::className(), 'targetAttribute' => ['sprint_id' => 'sprint_id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'usuario_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sprint_id' => 'Sprint ID',
            'usuario_id' => 'Usuario ID',
            'horas_desarrollo' => 'Horas Desarrollo',
            'observacion' => 'Observacion',
            'estado' => 'Estado',
            'sprintName' => 'Sprint Alias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['sprint_id' => 'sprint_id', 'usuario_asignado' => 'usuario_id']);
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
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_id']);
    }
    
    public function insertarSprintUsuarios($id, $key){
        
        $conexion = Yii::$app->db;
           
        $usuarios = explode(",",$key);
        foreach ($usuarios as $value) {
            /*
            $conexion->createCommand()->insert('sprint_usuarios', [
                'sprint_id' => $id,
                'usuario_id' => $value,
                'estado' => '1',
            ])->execute();    
            */
            $conexion->createCommand(" 
                UPDATE sprint_usuarios SET sprint_id=".$id.", usuario_id=".$value.", estado='1' WHERE sprint_id=".$id." and usuario_id=".$value.";
            ")->execute();
            
            $conexion->createCommand(" 
                INSERT INTO sprint_usuarios (sprint_id, usuario_id, estado)
                    SELECT ".$id.", ".$value.", '1'
                    WHERE NOT EXISTS (SELECT 1 FROM sprint_usuarios WHERE sprint_id=".$id." and usuario_id=".$value.");
            ")->execute();
          
        }     
        
        return true;  
        
    } 

    public function eliminarSprintUsuarios($id, $key){
        
        $conexion = Yii::$app->db;
       
           
        $usuarios = explode(",",$key);
        foreach ($usuarios as $value) {
            
            
            $conexion->createCommand()->update('sprint_usuarios', ['estado' => '0'], ['sprint_id' => $id, 'usuario_id' => $value])->execute();  
            
            /*
            $conexion->createCommand()->delete('sprint_usuarios', [
                'sprint_id' => $id,
                'usuario_id' => $value,
            ])->execute();   
            */
        }     
        
        return true;  
        
    }   
    
    public function getSprintName() {
        return $this->sprint->sprint_alias;
    }
    
    public static function getListaDesarrolladores($sprint_id){
        
        $opciones = SprintUsuarios::find()->where(['sprint_id'=>$sprint_id])->andWhere(['estado'=>'1'])->all();
        return ArrayHelper::map($opciones, 'usuario_id', 'usuario.nombreCompleto');
    }
}
