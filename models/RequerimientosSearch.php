<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Requerimientos;

/**
 * RequerimientosSearch represents the model behind the search form about `backend\models\Requerimientos`.
 */
class RequerimientosSearch extends Requerimientos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requerimiento_id', 'comite_id', 'usuario_solicita'], 'integer'],
            [['requerimiento_titulo', 'requerimiento_descripcion', 'requerimiento_justificacion', 'departamento_solicita', 'observaciones', 'fecha_requerimiento', 'estado'], 'safe'],
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
    public function search($params)
    {
        $query = Requerimientos::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['requerimiento_id'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'requerimiento_id' => $this->requerimiento_id,
            'comite_id' => $this->comite_id,
            'usuario_solicita' => $this->usuario_solicita,
            'fecha_requerimiento' => $this->fecha_requerimiento,
        ]);

        $query->andFilterWhere(['like', 'requerimiento_titulo', $this->requerimiento_titulo])
            ->andFilterWhere(['like', 'requerimiento_descripcion', $this->requerimiento_descripcion])
            ->andFilterWhere(['like', 'requerimiento_justificacion', $this->requerimiento_justificacion])
            ->andFilterWhere(['like', 'departamento_solicita', $this->departamento_solicita])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
