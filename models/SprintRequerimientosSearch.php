<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SprintRequerimientos;

/**
 * SprintRequerimientosSearch represents the model behind the search form of `app\models\SprintRequerimientos`.
 */
class SprintRequerimientosSearch extends SprintRequerimientos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'requerimiento_id', 'usuario_asignado', 'tiempo_desarrollo'], 'integer'],
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
    public function search($params, $sprint_id, $var)
    {
        

        // add conditions that should always apply here
        
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == 2 && $var == 2){
            $query = SprintRequerimientos::find()->where(['usuario_asignado' => Yii::$app->user->identity->usuario_id])->andWhere(['sprint_id' => $sprint_id]);
            //$query = SprintUsuarios::find();
        }else if($var == 1){
            $query = SprintRequerimientos::find()->where(['sprint_id' => $sprint_id]);
        }

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
            'requerimiento_id' => $this->requerimiento_id,
            'usuario_asignado' => $this->usuario_asignado,
            'tiempo_desarrollo' => $this->tiempo_desarrollo,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
