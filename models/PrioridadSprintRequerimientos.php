<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prioridad_sprint_requerimientos".
 *
 * @property int $prioridad_id
 * @property string $descripcion
 *
 * @property SprintRequerimientos[] $sprintRequerimientos
 */
class PrioridadSprintRequerimientos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prioridad_sprint_requerimientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prioridad_id', 'descripcion'], 'required'],
            [['prioridad_id'], 'default', 'value' => null],
            [['prioridad_id'], 'integer'],
            [['descripcion'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prioridad_id' => 'Prioridad ID',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['prioridad' => 'prioridad_id']);
    }
}
