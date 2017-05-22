<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SprintUsuarios;

/**
 * SprintUsuariosSearch represents the model behind the search form of `app\models\SprintUsuarios`.
 */
class SprintUsuariosSearch extends SprintUsuarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'usuario_id', 'horas_desarrollo'], 'integer'],
            [['observacion', 'estado'], 'safe'],
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
    public function search($params,$sprint_id = false)
    {
        //$query = SprintUsuarios::find();
        //$query = SprintUsuarios::find()->where(['sprint_id' => $sprint_id]);
        // add conditions that should always apply here
        
        if ($sprint_id != FALSE){     
            
            $query = SprintUsuarios::find()->where(['sprint_id' => $sprint_id]);
                       
        }else{
            $query = SprintUsuarios::find();
        }
        
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == 2){
            $query = SprintUsuarios::find()->where(['usuario_id' => Yii::$app->user->identity->usuario_id]);
            //$query = SprintUsuarios::find();
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
            'usuario_id' => $this->usuario_id,
            'horas_desarrollo' => $this->horas_desarrollo,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion])
            ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
