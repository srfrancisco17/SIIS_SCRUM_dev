<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "archivos".
 *
 * @property int $archivo_id
 * @property int $requerimiento_id
 * @property string $archivo_nombre
 * @property string $archivo_alias
 * @property string $archivo_peso
 * @property string $archivo_tipo
 *
 * @property Requerimientos $requerimiento
 */
class Archivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requerimiento_id', 'archivo_nombre', 'archivo_alias', 'archivo_peso', 'archivo_tipo'], 'required'],
            [['requerimiento_id'], 'default', 'value' => null],
            [['requerimiento_id'], 'integer'],
            [['archivo_nombre'], 'string', 'max' => 255],
            [['archivo_alias'], 'string', 'max' => 50],
            [['archivo_peso'], 'string', 'max' => 15],
            [['archivo_tipo'], 'string', 'max' => 25],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'archivo_id' => 'Archivo ID',
            'requerimiento_id' => 'Requerimiento ID',
            'archivo_nombre' => 'Archivo Nombre',
            'archivo_alias' => 'Archivo Alias',
            'archivo_peso' => 'Archivo Peso',
            'archivo_tipo' => 'Archivo Tipo',
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
