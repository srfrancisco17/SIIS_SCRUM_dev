<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SprintRequerimientosTareas;

/**
 * SprintRequerimientosTareasSearch represents the model behind the search form about `app\models\SprintRequerimientosTareas`.
 */
class SprintRequerimientosTareasSearch extends SprintRequerimientosTareas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarea_id', 'sprint_id', 'requerimiento_id'], 'integer'],
            [['estado'], 'safe'],
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
        $query = SprintRequerimientosTareas::find();

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
            'tarea_id' => $this->tarea_id,
            'sprint_id' => $this->sprint_id,
            'requerimiento_id' => $this->requerimiento_id,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
