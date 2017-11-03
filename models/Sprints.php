<?php

namespace app\models;
use yii\helpers\ArrayHelper;

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
            
            /*
            [['fecha_desde'], 'compare', 'compareAttribute'=>'fecha_hasta', 'operator'=>'!=', 'skipOnEmpty'=>true],
            [['fecha_hasta'], 'compare', 'compareAttribute'=>'fecha_desde', 'operator'=>'!='],
            */
            //[['fecha_desde'], 'compare', 'compareAttribute'=>'fecha_hasta', 'operator'=>'<=', 'skipOnEmpty'=>true],
            //[['fecha_hasta'], 'compare', 'compareAttribute'=>'fecha_desde', 'operator'=>'>='],
            
            [['horas_desarrollo'], 'default', 'value' => null],
            [['horas_desarrollo'], 'integer'],
            [['observaciones'], 'string'],
            [['sprint_alias'], 'string', 'max' => 60],
            [['estado'], 'string', 'max' => 2],
            [['estado'], 'checkEstado'],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['estado' => 'req_spr_id']],
        ]; 
    }
    
    public function checkEstado($attribute, $params)
    {
        
        if ($this->$attribute == 1){
            
            $estado_id = $this->estado;

            $connection = Yii::$app->db;
            $query = "SELECT COUNT(*) FROM sprints WHERE estado = '1'";

            $resultado = $connection->createCommand($query)->queryScalar();

            if ($resultado > 0){
                $this->addError($attribute, 'Ya Existe Un Sprint Activo');
            } 
                
        }
 
        //$this->addError($attribute, 'Ya Existe Un Sprint Activo');
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

    public static function getSprintActivo()
    {
        
        $sprint_activo = Sprints::findOne(['estado' => '1']);
        return $sprint_activo ? $sprint_activo : NULL;
    }

    public static function getListaSprints(){
        
        $opciones = Sprints::find()->orderBy(['sprint_id' => SORT_ASC])->all();
        
        return ArrayHelper::map($opciones, 'sprint_id', 'sprint_alias');
 
    }
    
    
    public function actualizarHorasSprints($sprint_id, $horas){

        $conexion = Yii::$app->db;

        $conexion->createCommand("UPDATE sprints SET horas_desarrollo=:horas WHERE sprint_id=:sprint_id")
        ->bindValue(':horas', $horas)
        ->bindValue(':sprint_id', $sprint_id)   
        ->execute();
        
        
    }
    
    public function terminarSprint($sprint_id){

        $connection = Yii::$app->db;
        
        $transaction = $connection->beginTransaction();
        
        /*
         * Consultas Utilizadas
         */
        
        
        /*
         * Cambiar el estado del sprint en 4(terminado)
         */ 
        $terminarSprint_sql = " 
            UPDATE
                sprints
            SET
                estado = '4'
            WHERE
                sprint_id = ".$sprint_id."
        ";
        /*
         * Buscar las tareas no terminadas del sprint
         */
        $tareasNoTerminadas_sql = "
            SELECT
                tarea_id,
                requerimiento_id
            FROM
                sprint_requerimientos_tareas
            WHERE
                sprint_id = ".$sprint_id."
            AND
                estado BETWEEN '2' AND '3'
        ";
        /*
         * Actualizacion de sprint_requerimientos cuando el estado se encuentre entre 2 y 3 
         */
        $requerimiento_noTerminado_sql = "
            UPDATE
                requerimientos AS re
            SET
                estado = '1'
            FROM
                sprint_requerimientos AS sr
            WHERE
                sr.estado BETWEEN '2' AND '3'
                AND sr.sprint_id = ".$sprint_id."
                AND sr.requerimiento_id = re.requerimiento_id
        ";
        
        /*
         * Actualizacion de sprint_requerimientos cuando el estado se encuentre entre 2 y 3 
         */
        $sprintRequerimiento_noTerminado_sql = "
            UPDATE
                sprint_requerimientos
            SET
                estado = '5'
            WHERE
                estado BETWEEN '2' AND '3'
                AND sprint_id = ".$sprint_id."
        ";
        

        try {
            
            //Ejecutar $terminarSprint_sql  
            $connection->createCommand($terminarSprint_sql)->execute();
            
            //Ejecutar $sprintRequerimiento_noTerminado_sql  
            $result_tareasNoTerminadas = $connection->createCommand($tareasNoTerminadas_sql)->queryAll();
            
            foreach ($result_tareasNoTerminadas as $value_tareas) {

                $update_requerimientosTareas_sql = "
                    UPDATE
                        requerimientos_tareas
                    SET
                        ultimo_estado = '5'
                    WHERE
                        tarea_id = ".$value_tareas['tarea_id']."
                        AND requerimiento_id = ".$value_tareas['requerimiento_id']."
                ";

                $connection->createCommand($update_requerimientosTareas_sql)->execute();
            }
             
            //Ejecutar $requerimiento_noTerminado_sql       
            $connection->createCommand($requerimiento_noTerminado_sql)->execute();  
            
            //Ejecutar $sprintRequerimiento_noTerminado_sql       
            $connection->createCommand($sprintRequerimiento_noTerminado_sql)->execute();  

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        //$query = SprintRequerimientosTareas::find()->select('tarea_id, requerimiento_id')->where(['sprint_id' => $sprint_id])->andWhere(['between', 'estado','2', '3'])->asArray()->all(); 
        
        
//        echo '<pre>';
//        print_r($result_tareasNoTerminadas);
//        exit;
        
        /*
        $conexion->createCommand("UPDATE sprints SET estado = 4 WHERE sprint_id=:sprint_id")
        ->bindValue(':sprint_id', $sprint_id)   
        ->execute();
        
        //
        $query = SprintRequerimientosTareas::find()->select('tarea_id, requerimiento_id')->where(['sprint_id' => $sprint_id])->andWhere(['between', 'estado','2', '3'])->all();
        
        if (!empty($query)){
            
                foreach ($query as $objTareas) {
                        
                    $conexion->createCommand()->insert('sprint_requerimientos_tareas', [
                        'tarea_id' => $objTareas->tarea_id,
                        'requerimiento_id' => $objTareas->requerimiento_id,
                        'estado' => '2',
                    ])->execute();
                    

                    $sql = "Update requerimientos_tareas set ultimo_estado = '5' Where tarea_id=".$objTareas->tarea_id." And requerimiento_id =".$objTareas->requerimiento_id;
                    $conexion->createCommand($sql)->execute();
                }
        }
         * 
         */
        //SprintRequerimientos::actualizarNoCumplido($sprint_id); 
         
         
    }
}
