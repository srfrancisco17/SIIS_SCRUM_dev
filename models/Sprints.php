<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sprints".
 *
 * @property int $sprint_id
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property int $horas_desarrollo
 * @property string $observaciones
 * @property string $estado
 *
 * @property SprintRequerimientos[] $sprintRequerimientos
 * @property Requerimientos[] $requerimientos
 * @property SprintRequerimientosTareas[] $sprintRequerimientosTareas
 * @property SprintUsuarios[] $sprintUsuarios
 * @property Usuarios[] $usuarios
 */
class Sprints extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sprints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_desde', 'fecha_hasta'], 'required'],
            [['fecha_desde', 'fecha_hasta'], 'date', 'format' => 'php:yy-m-d'],
            [['horas_desarrollo'], 'default', 'value' => null],
            [['horas_desarrollo'], 'integer'],
            [['observaciones'], 'string'],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sprint_id' => 'Sprint ID',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'horas_desarrollo' => 'Horas Desarrollo',
            'observaciones' => 'Observaciones',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id'])->viaTable('sprint_requerimientos', ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientosTareas()
    {
        return $this->hasMany(SprintRequerimientosTareas::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintUsuarios()
    {
        return $this->hasMany(SprintUsuarios::className(), ['sprint_id' => 'sprint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['usuario_id' => 'usuario_id'])->viaTable('sprint_usuarios', ['sprint_id' => 'sprint_id']);
    }
}
