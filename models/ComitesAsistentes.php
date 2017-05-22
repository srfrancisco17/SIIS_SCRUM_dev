<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comites_asistentes".
 *
 * @property int $comite_id
 * @property int $usuario_id
 * @property string $estado
 * @property string $sw_responsable
 * @property string $observacion
 *
 * @property Comites $comite
 * @property Usuarios $usuario
 */
class ComitesAsistentes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comites_asistentes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comite_id', 'usuario_id'], 'required'],
            [['comite_id', 'usuario_id'], 'default', 'value' => null],
            [['comite_id', 'usuario_id'], 'integer'],
            [['estado', 'sw_responsable'], 'string', 'max' => 1],
            [['observacion'], 'string', 'max' => 60],
            [['comite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comites::className(), 'targetAttribute' => ['comite_id' => 'comite_id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'usuario_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comite_id' => 'Comite ID',
            'usuario_id' => 'Usuario ID',
            'estado' => 'Estado',
            'sw_responsable' => 'Sw Responsable',
            'observacion' => 'Observacion',
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
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['usuario_id' => 'usuario_id']);
    }
    
    public function insertarUsuariosComite($id, $key){
        
        $conexion = Yii::$app->db;
       
           
        $usuarios = explode(",",$key);
        foreach ($usuarios as $value) {
            
            $conexion->createCommand()->insert('comites_asistentes', [
                'comite_id' => $id,
                'usuario_id' => $value,
            ])->execute();    
        }     
        
        return true;  
        
    }
}
