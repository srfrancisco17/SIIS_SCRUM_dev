<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Requerimientos;

/**
 * RequerimientosSearch represents the model behind the search form of `app\models\Requerimientos`.
 */
class RequerimientosCriteriosSearch extends Requerimientos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requerimiento_id', 'comite_id', 'usuario_solicita', 'tiempo_desarrollo', 'soporte_id'], 'integer'],
            [['requerimiento_titulo', 'requerimiento_descripcion', 'requerimiento_justificacion', 'departamento_solicita', 'observaciones', 'fecha_requerimiento', 'estado', 'sw_soporte', 'requerimiento_funcionalidad', 'divulgacion'], 'safe'],
            [['valor_total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
            'tiempo_desarrollo' => $this->tiempo_desarrollo,
            'soporte_id' => $this->soporte_id,
            'valor_total' => $this->valor_total,
        ]);

        $query->andFilterWhere(['ilike', 'requerimiento_titulo', $this->requerimiento_titulo])
            ->andFilterWhere(['ilike', 'requerimiento_descripcion', $this->requerimiento_descripcion])
            ->andFilterWhere(['ilike', 'requerimiento_justificacion', $this->requerimiento_justificacion])
            ->andFilterWhere(['ilike', 'departamento_solicita', $this->departamento_solicita])
            ->andFilterWhere(['ilike', 'observaciones', $this->observaciones])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'sw_soporte', $this->sw_soporte])
            ->andFilterWhere(['ilike', 'requerimiento_funcionalidad', $this->requerimiento_funcionalidad])
            ->andFilterWhere(['ilike', 'divulgacion', $this->divulgacion]);

        return $dataProvider;
    }
}