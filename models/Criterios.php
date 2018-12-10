<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "criterios".
 *
 * @property int $criterio_id
 * @property string $descripcion
 * @property string $descripcion_abreviada
 * @property string $estado
 * @property int $orden
 * @property string $valor
 *
 * @property RequerimientosCriterios[] $requerimientosCriterios
 * @property Requerimientos[] $requerimientos
 */
class Criterios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'criterios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'estado', 'valor'], 'required'],
            [['orden'], 'default', 'value' => null],
            [['orden'], 'integer'],
            [['valor'], 'number'],
            [['descripcion'], 'string', 'max' => 80],
            [['descripcion_abreviada'], 'string', 'max' => 10],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'criterio_id' => 'Criterio ID',
            'descripcion' => 'Descripcion',
            'descripcion_abreviada' => 'Descripcion Abreviada',
            'estado' => 'Estado',
            'orden' => 'Orden',
            'valor' => 'Valor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientosCriterios()
    {
        return $this->hasMany(RequerimientosCriterios::className(), ['criterio_id' => 'criterio_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id'])->viaTable('requerimientos_criterios', ['criterio_id' => 'criterio_id']);
    }
}
