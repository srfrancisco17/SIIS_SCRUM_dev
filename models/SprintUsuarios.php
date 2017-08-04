<?php

namespace app\models;


use yii\db\Query;
use yii\helpers\ArrayHelper;
use Yii;
use app\models\Requerimientos;

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
        return $this->hasMany(SprintRequerimientos::className(), ['sprint_id' => 'sprint_id', 'usuario_asignado' => 'usuario_id'])->
        orderBy(['prioridad' => SORT_ASC]);
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
        
        
        $sprint_alias = Sprints::find()->select('sprint_alias')->where(['sprint_id' => $id])->one()->sprint_alias;
        
        $prioridad_id = $conexion->createCommand("select prioridad_id from prioridad_sprint_requerimientos order by prioridad_id DESC limit 1")->queryScalar();
        
        foreach ($usuarios as $value) {
            

            $conexion->createCommand(" 
                UPDATE sprint_usuarios SET sprint_id=".$id.", usuario_id=".$value.", estado='1' WHERE sprint_id=".$id." and usuario_id=".$value.";
            ")->execute();
            
            $conexion->createCommand(" 
                INSERT INTO sprint_usuarios (sprint_id, usuario_id, estado)
                    SELECT ".$id.", ".$value.", '1'
                    WHERE NOT EXISTS (SELECT 1 FROM sprint_usuarios WHERE sprint_id=".$id." and usuario_id=".$value.");
            ")->execute();
            
            self::requerimiento_soporte($value, $id, $sprint_alias, $prioridad_id);
          
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
    
    public function requerimiento_soporte($usuario_asignado, $sprint_id, $sprint_alias, $prioridad_id){
        
        $conexion = Yii::$app->db;
        
        $var_soporte = $conexion->createCommand("
            SELECT 
                COUNT(*)
            FROM
            sprint_requerimientos AS sr
            INNER JOIN requerimientos r
            ON(
                sr.requerimiento_id = r.requerimiento_id
            )
            WHERE sw_soporte = '1' AND usuario_asignado = :usuario_asignado AND sprint_id = :sprint_id
        ")
        ->bindValue(':usuario_asignado', $usuario_asignado)
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();

        if ($var_soporte == 0){
            
            date_default_timezone_set('America/Bogota');

            
            $model_requerimientos = new Requerimientos();
            $model_requerimientos->requerimiento_titulo = 'Soporte '.$sprint_alias;
            $model_requerimientos->usuario_solicita = 1;
            $model_requerimientos->departamento_solicita = '1';
            $model_requerimientos->fecha_requerimiento = date('Y-m-d');
            $model_requerimientos->estado = '2';
            $model_requerimientos->sw_soporte = '1';
            
            $model_requerimientos->save();

            
            $conexion->createCommand()
            ->insert('sprint_requerimientos', [
                'sprint_id' => $sprint_id,
		'requerimiento_id' => $model_requerimientos->requerimiento_id,
                'usuario_asignado' => $usuario_asignado,
                'estado' => '2',
                'prioridad' => $prioridad_id,
            ])->execute();
            

        }

    }
}
