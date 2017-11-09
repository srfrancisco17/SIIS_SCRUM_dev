<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequerimientosTareas;

/**
 * RequerimientosTareasSearch represents the model behind the search form about `app\models\RequerimientosTareas`.
 */
class RequerimientosTareasSearch extends RequerimientosTareas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarea_id', 'requerimiento_id', 'horas_desarrollo'], 'integer'],
            [['tarea_titulo', 'tarea_descripcion', 'ultimo_estado', 'fecha_terminado'], 'safe'],
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
        $query = RequerimientosTareas::find()->where(['requerimiento_id' => $requerimiento_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['tarea_id'=>SORT_ASC]]
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
            'requerimiento_id' => $this->requerimiento_id,
            'horas_desarrollo' => $this->horas_desarrollo,
            'fecha_terminado' => $this->fecha_terminado,
        ]);

        $query->andFilterWhere(['like', 'tarea_titulo', $this->tarea_titulo])
            ->andFilterWhere(['like', 'tarea_descripcion', $this->tarea_descripcion])
            ->andFilterWhere(['like', 'ultimo_estado', $this->ultimo_estado]);

        return $dataProvider;
    }
}
