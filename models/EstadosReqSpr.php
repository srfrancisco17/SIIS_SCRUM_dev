<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estados_req_spr".
 *
 * @property string $req_spr_id
 * @property string $descripcion
 * @property string $sw_requerimiento
 * @property string $sw_sprint_req
 * @property string $sw_sprint_req_tarea
 * @property string $sw_sprint
 *
 * @property Requerimientos[] $requerimientos
 * @property SprintRequerimientos[] $sprintRequerimientos
 * @property SprintRequerimientosTareas[] $sprintRequerimientosTareas
 * @property Sprints[] $sprints
 */
class EstadosReqSpr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estados_req_spr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['req_spr_id', 'descripcion'], 'required'],
            [['req_spr_id'], 'string', 'max' => 2],
            [['descripcion'], 'string', 'max' => 30],
            [['sw_requerimiento', 'sw_sprint_req', 'sw_sprint_req_tarea', 'sw_sprint'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_spr_id' => 'Req Spr ID',
            'descripcion' => 'Descripcion',
            'sw_requerimiento' => 'Sw Requerimiento',
            'sw_sprint_req' => 'Sw Sprint Req',
            'sw_sprint_req_tarea' => 'Sw Sprint Req Tarea',
            'sw_sprint' => 'Sw Sprint',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['estado' => 'req_spr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientos()
    {
        return $this->hasMany(SprintRequerimientos::className(), ['estado' => 'req_spr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintRequerimientosTareas()
    {
        return $this->hasMany(SprintRequerimientosTareas::className(), ['estado' => 'req_spr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprints()
    {
        return $this->hasMany(Sprints::className(), ['estado' => 'req_spr_id']);
    }
}
