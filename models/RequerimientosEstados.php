<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requerimientos_estados".
 *
 * @property string $reqest_id
 * @property string $descripcion
 *
 * @property Requerimientos[] $requerimientos
 */
class RequerimientosEstados extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimientos_estados';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reqest_id', 'descripcion'], 'required'],
            [['reqest_id'], 'string', 'max' => 2],
            [['descripcion'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reqest_id' => 'Reqest ID',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['estado' => 'reqest_id']);
    }
}
