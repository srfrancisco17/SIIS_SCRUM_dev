<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipos_documentos".
 *
 * @property string $documento_id
 * @property string $descripcion
 *
 * @property Usuarios[] $usuarios
 */
class TiposDocumentos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_documentos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documento_id'], 'required'],
            [['documento_id'], 'string', 'max' => 3],
            [['descripcion'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'documento_id' => 'Documento ID',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['tipo_documento' => 'documento_id']);
    }
}