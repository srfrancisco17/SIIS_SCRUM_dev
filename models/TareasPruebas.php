<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tareas_pruebas".
 *
 * @property integer $id
 * @property integer $prueba_id
 * @property integer $tarea_id
 * @property string $estado
 *
 * @property RequerimientosPruebas $prueba
 * @property RequerimientosTareas $tarea
 */
class TareasPruebas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tareas_pruebas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prueba_id', 'tarea_id', 'estado'], 'required'],
            [['prueba_id', 'tarea_id'], 'integer'],
            [['estado'], 'string', 'max' => 1],
            [['prueba_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequerimientosPruebas::className(), 'targetAttribute' => ['prueba_id' => 'prueba_id']],
            [['tarea_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequerimientosTareas::className(), 'targetAttribute' => ['tarea_id' => 'tarea_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prueba_id' => 'Prueba ID',
            'tarea_id' => 'Tarea ID',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrueba()
    {
        return $this->hasOne(RequerimientosPruebas::className(), ['prueba_id' => 'prueba_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarea()
    {
        return $this->hasOne(RequerimientosTareas::className(), ['tarea_id' => 'tarea_id']);
    }
     
    
}
