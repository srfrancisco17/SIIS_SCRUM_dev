<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requerimientos_pruebas".
 *
 * @property integer $prueba_id
 * @property string $fecha_entrega
 * @property string $fecha_prueba
 * @property string $estado
 * @property integer $usuario_pruebas
 * @property integer $requerimiento_id
 * @property string $observaciones
 *
 * @property Requerimientos $requerimiento
 * @property Usuarios $usuarioPruebas
 */
class RequerimientosPruebas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimientos_pruebas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_entrega', 'fecha_prueba', 'estado', 'usuario_pruebas'], 'required'],
            [['fecha_entrega', 'fecha_prueba'], 'safe'],
            [['usuario_pruebas', 'requerimiento_id'], 'integer'],
            [['observaciones'], 'string'],
            [['estado'], 'string', 'max' => 1],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
            [['usuario_pruebas'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_pruebas' => 'usuario_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prueba_id' => 'Prueba ID',
            'fecha_entrega' => 'Fecha Entrega',
            'fecha_prueba' => 'Fecha Prueba',
            'estado' => 'Estado',
            'usuario_pruebas' => 'Usuario Pruebas',
            'requerimiento_id' => 'Requerimiento ID',
            'observaciones' => 'Observaciones',
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
    public function getUsuarioPruebas()
    {
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_pruebas']);
    }
}
