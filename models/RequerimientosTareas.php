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
 * @property string $sw_urgente
 */
class RequerimientosTareas extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'requerimientos_tareas';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['requerimiento_id', 'tarea_titulo'], 'required'],
            [['requerimiento_id'], 'integer'],
            //['horas_desarrollo', 'integer', 'max' => 8, 'message' => 'Horas no puede ser MAYOR a 8.'],
            //['horas_desarrollo', 'integer', 'min' => 1, 'message' => 'Horas no puede ser MENOR a 1.'],
            ['horas_desarrollo', 'validateHoras'],
            [['tarea_descripcion'], 'string'],
            [['fecha_terminado'], 'safe'],
            [['tarea_titulo'], 'string', 'max' => 60],
            [['ultimo_estado'], 'string', 'max' => 2],
            [['sw_urgente'], 'string', 'max' => 1],
            [['ultimo_estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosReqSpr::className(), 'targetAttribute' => ['ultimo_estado' => 'req_spr_id']],
            [['requerimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimientos::className(), 'targetAttribute' => ['requerimiento_id' => 'requerimiento_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tarea_id' => 'Tarea ID',
            'requerimiento_id' => 'Requerimiento ID',
            'tarea_titulo' => 'Tarea Titulo',
            'tarea_descripcion' => 'Tarea Descripcion',
            'ultimo_estado' => 'Ultimo Estado',
            'horas_desarrollo' => 'Horas Desarrollo',
            'fecha_terminado' => 'Fecha Terminado',
            'sw_urgente' => 'Sw Urgente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUltimoEstado() {
        return $this->hasOne(EstadosReqSpr::className(), ['req_spr_id' => 'ultimo_estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimiento() {
        return $this->hasOne(Requerimientos::className(), ['requerimiento_id' => 'requerimiento_id']);
    }

    public function getSprintRequerimientosTareas() {
        return $this->hasOne(SprintRequerimientosTareas::className(), ['tarea_id' => 'tarea_id']);
    }

    public function validateHoras($attribute, $params, $validator) {

        $tipo_usuario = Yii::$app->user->identity->tipo_usuario;
        $requerimiento_id = $this->requerimiento_id;
        $sprint_id = $_REQUEST['sprint_id'];

        if ($this->horas_desarrollo > 8) {

            $this->addError($attribute, "Las Horas no pueden ser MAYOR a 8.");
        } else if ($this->horas_desarrollo <= 0) {

            $this->addError($attribute, "Las Horas no pueden ser MENOR a 1.");
        }


        if ($tipo_usuario == '2') {

            /*
             * Si el usuario es desarrollador aplicara la validacion de las horas de soporte
             */
            $requerimiento = (new \yii\db\Query())
                    ->select('
                        R.requerimiento_id,
                        R.requerimiento_titulo,
                        R.departamento_solicita,
                        R.estado,
                        R.sw_soporte,
                        SU.usuario_id,
                        SR.tiempo_desarrollo,
                        SU.horas_establecidas_soporte
                    ')
                    ->from('requerimientos R')
                    ->innerJoin('sprint_requerimientos SR', '"SR"."sprint_id" = ' . $sprint_id . ' AND "SR"."requerimiento_id" = "R"."requerimiento_id"')
                    ->innerJoin('sprint_usuarios SU', '"SU"."sprint_id" = "SR"."sprint_id" AND "SU"."usuario_id" = "SR"."usuario_asignado"')
                    ->where(['R.requerimiento_id' => $requerimiento_id])
                    ->andWhere(['R.sw_soporte' => '1'])
                    ->one();

//            echo '<pre>';
//            var_dump($requerimiento);
//            exit;

            if (!empty($requerimiento)) {

                $valor_nuevo = 0;
                $tarea = RequerimientosTareas::findOne($this->tarea_id);

                if (!empty($tarea)) {


                    $operacion = ($this->horas_desarrollo >= $tarea->horas_desarrollo) ? "SUMA" : "RESTA";


                    if ($operacion == "SUMA") {

                        $valor_nuevo = $requerimiento['tiempo_desarrollo'] + ($this->horas_desarrollo - $tarea->horas_desarrollo);
                    } else if ($operacion == "RESTA") {

                        $valor_nuevo = $requerimiento['tiempo_desarrollo'] - ($tarea->horas_desarrollo - $this->horas_desarrollo);
                    }
                } else {

                    $valor_nuevo = $requerimiento['tiempo_desarrollo'] + $this->horas_desarrollo;
                }


                if ($requerimiento['horas_establecidas_soporte'] < $valor_nuevo) {

                    $this->addError($attribute, "Horas de soporte excedidas");
                }
            }
        }


        return true;
    }

}
