<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipos_usuarios".
 *
 * @property string $tipo_usuario_id
 * @property string $descripcion
 * @property string $estado
 *
 * @property Usuarios[] $usuarios
 */
class TiposUsuarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_usuario_id'], 'required'],
            [['tipo_usuario_id'], 'number'],
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
            'tipo_usuario_id' => '(*) Tipo Usuario ID:',
            'descripcion' => 'Descripcion:',
            'estado' => 'Estado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['tipo_usuario' => 'tipo_usuario_id']);
    }
}
