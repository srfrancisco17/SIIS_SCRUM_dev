<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "procesos_involucrados". 
 * 
 * @property integer $id
 * @property string $descripcion
 * @property integer $requerimiento_id
 * 
 * @property Requerimientos $requerimiento
 */ 
class ProcesosInvolucrados extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return 'procesos_involucrados'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['descripcion', 'requerimiento_id'], 'required'],
            [['requerimiento_id'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'requerimiento_id' => 'Requerimiento ID',
        ]; 
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getRequerimiento() 
    { 
        return $this->hasOne(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    } 
} 