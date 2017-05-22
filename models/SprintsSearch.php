<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sprints;

/**
 * SprintsSearch represents the model behind the search form of `app\models\Sprints`.
 */
class SprintsSearch extends Sprints
{
    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [ 
            [['sprint_id', 'horas_desarrollo'], 'integer'],
            [['sprint_alias', 'fecha_desde', 'fecha_hasta', 'observaciones', 'estado'], 'safe'], 
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
        $query = Sprints::find();

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
            'sprint_id' => $this->sprint_id,
            'fecha_desde' => $this->fecha_desde,
            'fecha_hasta' => $this->fecha_hasta,
            'horas_desarrollo' => $this->horas_desarrollo,
        ]);

        $query->andFilterWhere(['ilike', 'sprint_alias', $this->sprint_alias])
            ->andFilterWhere(['ilike', 'observaciones', $this->observaciones])
            ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
