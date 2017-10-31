<?php

namespace app\models; 

use Yii; 
use yii\base\Model; 
use yii\data\ActiveDataProvider; 
use app\models\TiposDocumentos; 

/**
 * TiposDocumentosSearch represents the model behind the search form of `app\models\TiposDocumentos`.
 */
class TiposDocumentosSearch extends TiposDocumentos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documento_id', 'descripcion'], 'safe'],
            [['orden'], 'integer'], 
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
        $query = TiposDocumentos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['orden'=>SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'orden' => $this->orden,
        ]);
        
        $query->andFilterWhere(['ilike', 'documento_id', $this->documento_id])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}