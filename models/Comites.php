<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "comites". 
 * 
 * @property integer $comite_id
 * @property string $comite_alias
 * @property string $fecha
 * @property string $hora_desde
 * @property string $hora_hasta
 * @property integer $dirigido_por
 * @property string $lugar
 * @property string $estado
 * 
 * @property ComitesAsistentes[] $comitesAsistentes
 * @property Usuarios[] $usuarios
 * @property Requerimientos[] $requerimientos
 */ 
class Comites extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return 'comites'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['comite_alias'], 'required'],
            [['fecha', 'hora_desde', 'hora_hasta'], 'safe'],
            [['dirigido_por'], 'integer'],
            [['comite_alias'], 'string', 'max' => 60],
            [['lugar'], 'string', 'max' => 30],
            [['estado'], 'string', 'max' => 1],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'comite_id' => 'Comite ID',
            'comite_alias' => 'Comite Alias',
            'fecha' => 'Fecha',
            'hora_desde' => 'Hora Desde',
            'hora_hasta' => 'Hora Hasta',
            'dirigido_por' => 'Dirigido Por',
            'lugar' => 'Lugar',
            'estado' => 'Estado',
        ]; 
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getComitesAsistentes() 
    { 
        return $this->hasMany(ComitesAsistentes::className(), ['comite_id' => 'comite_id']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUsuarios() 
    { 
        return $this->hasMany(Usuarios::className(), ['usuario_id' => 'usuario_id'])->viaTable('comites_asistentes', ['comite_id' => 'comite_id']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getRequerimientos() 
    { 
        return $this->hasMany(Requerimientos::className(), ['comite_id' => 'comite_id']);
    } 
} 