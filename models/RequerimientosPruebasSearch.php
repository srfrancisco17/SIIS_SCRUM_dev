<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequerimientosPruebas;

/**
 * RequerimientosPruebasSearch represents the model behind the search form about `app\models\RequerimientosPruebas`.
 */
class RequerimientosPruebasSearch extends RequerimientosPruebas
{
    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [ 
            [['prueba_id', 'usuario_pruebas', 'requerimiento_id', 'sprint_id'], 'integer'],
            [['fecha_entrega', 'fecha_prueba', 'estado', 'observaciones'], 'safe'], 
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
    public function search($params, $requerimiento_id)
    {
        $query = RequerimientosPruebas::find()->where(['requerimiento_id' => $requerimiento_id]);

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
            'prueba_id' => $this->prueba_id,
            'fecha_entrega' => $this->fecha_entrega,
            'fecha_prueba' => $this->fecha_prueba,
            'usuario_pruebas' => $this->usuario_pruebas,
            'requerimiento_id' => $this->requerimiento_id,
            'sprint_id' => $this->sprint_id,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
