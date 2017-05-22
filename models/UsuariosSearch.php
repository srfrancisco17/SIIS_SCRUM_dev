<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuarios;

/**
 * UsuariosSearch represents the model behind the search form of `app\models\Usuarios`.
 */
class UsuariosSearch extends Usuarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id', 'estado'], 'integer'],
            [['num_documento', 'tipo_documento', 'nombres', 'apellidos', 'descripcion', 'correo', 'telefono', 'contrasena', 'departamento', 'color'], 'safe'],
            [['tipo_usuario'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $var = false, $comite_id = false)
    {
        /*
         * Condicional que 
         */
        if($var == 1){
            $var = ComitesAsistentes::findBySql('select usuario_id FROM comites_asistentes WHERE comite_id='.$comite_id)->asArray()->all();
            $query = Usuarios::find()->where(['not in','usuario_id', $var])->andWhere(['estado'=>'1']);
        }elseif ($var == 2) {
            //Condicion
            $var2 = SprintUsuarios::findBySql('select usuario_id FROM sprint_usuarios WHERE sprint_id='.$comite_id)->asArray()->all();
            $query = Usuarios::find()->where(['not in','usuario_id', $var2])->andWhere(['tipo_usuario' => 2]);
        }
        else{
            $query = Usuarios::find();
        } 
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'usuario_id' => $this->usuario_id,
            'tipo_usuario' => $this->tipo_usuario,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['ilike', 'num_documento', $this->num_documento])
            ->andFilterWhere(['ilike', 'tipo_documento', $this->tipo_documento])
            ->andFilterWhere(['ilike', 'nombres', $this->nombres])
            ->andFilterWhere(['ilike', 'apellidos', $this->apellidos])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'correo', $this->correo])
            ->andFilterWhere(['ilike', 'telefono', $this->telefono])
            ->andFilterWhere(['ilike', 'contrasena', $this->contrasena])
            ->andFilterWhere(['ilike', 'departamento', $this->departamento])
            ->andFilterWhere(['ilike', 'color', $this->color]);

        return $dataProvider;
    }
}
