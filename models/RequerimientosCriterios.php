<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requerimientos_criterios".
 *
 * @property int $requerimiento_id
 * @property int $criterio_id
 * @property int $valor
 *
 * @property Criterios $criterio
 * @property Requerimientos $requerimiento
 */
class RequerimientosCriterios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requerimientos_criterios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['criterio_id'], 'required'],
            [['criterio_id', 'valor'], 'default', 'value' => null],
            [['criterio_id', 'valor'], 'integer'],
            [['criterio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Criterios::className(), 'targetAttribute' => ['criterio_id' => 'criterio_id']],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'requerimiento_id' => 'Requerimiento ID',
            'criterio_id' => 'Criterio ID',
            'valor' => 'Valor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterio()
    {
        return $this->hasOne(Criterios::className(), ['criterio_id' => 'criterio_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimiento()
    {
        return $this->hasOne(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
}
