<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requerimientos".
 *
 * @property int $requerimiento_id
 * @property int $comite_id
 * @property string $requerimiento_titulo
 * @property string $requerimiento_descripcion
 * @property string $requerimiento_justificacion
 * @property int $usuario_solicita
 * @property string $departamento_solicita
 * @property string $observaciones
 * @property string $fecha_requerimiento
 * @property string $estado
 *
 * @property Comites $comite
 * @property Departamentos $departamentoSolicita
 * @property RequerimientosEstados $estado0
 * @property Usuarios $usuarioSolicita
 * @property SprintRequerimientos[] $sprintRequerimientos
 * @property Sprints[] $sprints
 * @property SprintRequerimientosTareas[] $sprintRequerimientosTareas
 */
class Requerimientos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comite_id', 'usuario_solicita'], 'default', 'value' => null],
            [['comite_id', 'usuario_solicita'], 'integer'],
            [['requerimiento_titulo', 'usuario_solicita', 'fecha_requerimiento', 'estado'], 'required'],
            [['requerimiento_descripcion', 'requerimiento_justificacion', 'observaciones'], 'string'],
            [['fecha_requerimiento'], 'safe'],
            [['requerimiento_titulo'], 'string', 'max' => 60],
            //[['departamento_solicita'], 'string', 'max' => 4],
            [['estado'], 'string', 'max' => 2],
            [['comite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comites::className(), 'targetAttribute' => ['comite_id' => 'comite_id']],
            //[['departamento_solicita'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['departamento_solicita' => 'departamento_id']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => RequerimientosEstados::className(), 'targetAttribute' => ['estado' => 'reqest_id']],
            [['usuario_solicita'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_solicita' => 'usuario_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'requerimiento_id' => 'Requerimiento ID',
            'comite_id' => 'Comite ID',
            'requerimiento_titulo' => 'Requerimiento Titulo',
            'requerimiento_descripcion' => 'Requerimiento Descripcion',
            'requerimiento_justificacion' => 'Requerimiento Justificacion',
            'usuario_solicita' => 'Usuario Solicita',
            'departamento_solicita' => 'Departamento Solicita',
            'observaciones' => 'Observaciones',
            'fecha_requerimiento' => 'Fecha Requerimiento',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComite()
    {
        return $this->hasOne(Comites::className(), ['comite_id' => 'comite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamentoSolicita()
    {
        return $this->hasOne(Departamentos::className(), ['departamento_id' => 'departamento_solicita']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(RequerimientosEstados::className(), ['reqest_id' => 'estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioSolicita()
    {
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_solicita']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprints()
    {
        return $this->hasMany(Sprints::className(), ['sprint_id' => 'sprint_id'])->viaTable('sprint_requerimientos', ['requerimiento_id' => 'requerimiento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientosTareas()
    {
        return $this->hasMany(SprintRequerimientosTareas::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
}
