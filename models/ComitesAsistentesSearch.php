<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ComitesAsistentes;

/**
 * ComitesAsistentesSearch represents the model behind the search form of `app\models\ComitesAsistentes`.
 */
class ComitesAsistentesSearch extends ComitesAsistentes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comite_id', 'usuario_id'], 'integer'],
            [['estado', 'sw_responsable', 'observacion'], 'safe'],
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
    public function search($params, $comite_id)
    {
        $query = ComitesAsistentes::find()->where(['comite_id' => $comite_id]);

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
            'comite_id' => $this->comite_id,
            'usuario_id' => $this->usuario_id,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'sw_responsable', $this->sw_responsable])
            ->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
