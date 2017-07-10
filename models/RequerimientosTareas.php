<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requerimientos_tareas".
 *
 * @property integer $tarea_id
 * @property integer $requerimiento_id
 * @property string $tarea_titulo
 * @property string $tarea_descripcion
 * @property string $ultimo_estado
 * @property integer $horas_desarrollo
 * @property string $fecha_terminado
 *
 * @property EstadosReqSpr $ultimoEstado
 * @property Requerimientos $requerimiento
 */
class RequerimientosTareas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimientos_tareas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requerimiento_id', 'tarea_titulo'], 'required'],
            [['requerimiento_id', 'horas_desarrollo'], 'integer'],
            [['tarea_descripcion'], 'string'],
            [['fecha_terminado'], 'safe'],
            [['tarea_titulo'], 'string', 'max' => 60],
            [['ultimo_estado'], 'string', 'max' => 2],
            [['ultimo_estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['ultimo_estado' => 'req_spr_id']],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarea_id' => 'Tarea ID',
            'requerimiento_id' => 'Requerimiento ID',
            'tarea_titulo' => 'Tarea Titulo',
            'tarea_descripcion' => 'Tarea Descripcion',
            'ultimo_estado' => 'Ultimo Estado',
            'horas_desarrollo' => 'Horas Desarrollo',
            'fecha_terminado' => 'Fecha Terminado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUltimoEstado()
    {
        return $this->hasOne(EstadosReqSpr::className(), ['req_spr_id' => 'ultimo_estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimiento()
    {
        return $this->hasOne(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }
}
