<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PerfilesUsuariosImpactados;

/**
 * PerfilesUsuariosImpactadosSearch represents the model behind the search form about `app\models\PerfilesUsuariosImpactados`.
 */
class PerfilesUsuariosImpactadosSearch extends PerfilesUsuariosImpactados
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'requerimiento_id'], 'integer'],
            [['descripcion'], 'safe'],
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
        $query = PerfilesUsuariosImpactados::find()->where(['requerimiento_id' => $requerimiento_id]);

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
            'id' => $this->id,
            'requerimiento_id' => $this->requerimiento_id,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
