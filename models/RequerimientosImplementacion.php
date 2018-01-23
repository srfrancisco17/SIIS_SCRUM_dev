<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "requerimientos_implementacion".
 *
 * @property integer $requerimiento_id
 * @property integer $soporte_entregado_por
 * @property string $soporte_entregado_fecha
 * @property integer $soporte1_recibio_capacitacion
 * @property string $soporte1_fecha_entrega
 * @property integer $soporte2_recibio_capacitacion
 * @property string $soporte2_fecha_entrega
 * @property integer $usuario_aprueba_produccion
 * @property string $fecha_subida_produccion
 * @property integer $produccion_entregado_por
 * @property string $produccion_entregado_fecha
 * @property integer $produccion1_recibio_capacitacion
 * @property string $produccion1_fecha_entrega
 * @property integer $produccion2_recibio_capacitacion
 * @property string $produccion2_fecha_entrega
 * @property integer $usuario_recibe
 *
 * @property Requerimientos $requerimiento
 */
class RequerimientosImplementacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimientos_implementacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requerimiento_id'], 'required'],
            [['requerimiento_id', 'soporte_entregado_por', 'soporte1_recibio_capacitacion', 'soporte2_recibio_capacitacion', 'usuario_aprueba_produccion', 'produccion_entregado_por', 'produccion1_recibio_capacitacion', 'produccion2_recibio_capacitacion', 'usuario_recibe'], 'integer'],
            [['soporte_entregado_fecha', 'soporte1_fecha_entrega', 'soporte2_fecha_entrega', 'fecha_subida_produccion', 'produccion_entregado_fecha', 'produccion1_fecha_entrega', 'produccion2_fecha_entrega'], 'safe'],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'requerimiento_id' => 'Requerimiento ID',
            'soporte_entregado_por' => 'Soporte Entregado Por',
            'soporte_entregado_fecha' => 'Soporte Entregado Fecha',
            'soporte1_recibio_capacitacion' => 'Soporte1 Recibio Capacitacion',
            'soporte1_fecha_entrega' => 'Soporte1 Fecha Entrega',
            'soporte2_recibio_capacitacion' => 'Soporte2 Recibio Capacitacion',
            'soporte2_fecha_entrega' => 'Soporte2 Fecha Entrega',
            'usuario_aprueba_produccion' => 'Usuario Aprueba Produccion',
            'fecha_subida_produccion' => 'Fecha Subida Produccion',
            'produccion_entregado_por' => 'Produccion Entregado Por',
            'produccion_entregado_fecha' => 'Produccion Entregado Fecha',
            'produccion1_recibio_capacitacion' => 'Produccion1 Recibio Capacitacion',
            'produccion1_fecha_entrega' => 'Produccion1 Fecha Entrega',
            'produccion2_recibio_capacitacion' => 'Produccion2 Recibio Capacitacion',
            'produccion2_fecha_entrega' => 'Produccion2 Fecha Entrega',
            'usuario_recibe' => 'Usuario Recibe',
        ];
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
    public function getSoporteEntregadoPor() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'soporte_entregado_por']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getSoporte1RecibioCapacitacion() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'soporte1_recibio_capacitacion']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getSoporte2RecibioCapacitacion() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'soporte2_recibio_capacitacion']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUsuarioApruebaProduccion() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_aprueba_produccion']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getProduccionEntregadoPor() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'produccion_entregado_por']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getProduccion1RecibioCapacitacion() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'produccion1_recibio_capacitacion']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getProduccion2RecibioCapacitacion() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'produccion2_recibio_capacitacion']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUsuarioRecibe() 
    { 
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_recibe']);
    } 

    
}
