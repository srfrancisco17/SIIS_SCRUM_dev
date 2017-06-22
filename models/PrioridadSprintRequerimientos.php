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
    
    public static function getSubCatList($sprint_id, $usuario_id) {
        
        $sql = "Select prioridad_id AS id, descripcion AS name From prioridad_sprint_requerimientos where Not prioridad_id In (SELECT CASE WHEN prioridad is NULL THEN 99 ELSE prioridad END  from sprint_requerimientos where sprint_id = ".$sprint_id." and usuario_asignado = ".$usuario_id.") order by prioridad_id";
        
        $prioridades = \app\models\PrioridadSprintRequerimientos::findBySql($sql)->asArray()->all();
        
        return $prioridades;
    }
}
