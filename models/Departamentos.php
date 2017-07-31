<?php

namespace app\models;

use Yii;

/** 
 * This is the model class for table "departamentos". 
 * 
 * @property string $departamento_id
 * @property string $descripcion
 * @property string $estado
 * 
 * @property Requerimientos[] $requerimientos
 * @property Usuarios[] $usuarios
 */
class Departamentos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departamentos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['departamento_id'], 'required'],
            [['departamento_id'], 'string', 'max' => 4],
            [['descripcion'], 'string', 'max' => 60],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departamento_id' => 'Departamento ID',
            'descripcion' => 'Descripcion',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['departamento_solicita' => 'departamento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['departamento' => 'departamento_id']);
    }
}