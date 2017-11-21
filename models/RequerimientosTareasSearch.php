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
    public function search($params, $sprint_id, $requerimiento_id)
    {
        // sprint_id -> si se envia este parametro entonces se realiza la condicion con el sprint
        if (!empty($sprint_id)){
            
            $query = RequerimientosTareas::find()
                ->innerJoinWith('sprintRequerimientosTareas')
                ->where(['sprint_requerimientos_tareas.sprint_id' => $sprint_id])
                ->andWhere(['requerimientos_tareas.requerimiento_id' => $requerimiento_id]);            
        }else{
            $query = RequerimientosTareas::find()->where(['requerimiento_id' => $requerimiento_id]);
        }
        
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
            'requerimientos_tareas.tarea_id' => $this->tarea_id,
            'requerimientos_tareas.requerimiento_id' => $this->requerimiento_id,
            'requerimientos_tareas.horas_desarrollo' => $this->horas_desarrollo,
            'requerimientos_tareas.fecha_terminado' => $this->fecha_terminado,
        ]);

        $query->andFilterWhere(['like', 'requerimientos_tareas.tarea_titulo', $this->tarea_titulo])
            ->andFilterWhere(['like', 'requerimientos_tareas.tarea_descripcion', $this->tarea_descripcion])
            ->andFilterWhere(['like', 'requerimientos_tareas.ultimo_estado', $this->ultimo_estado]);

        return $dataProvider;
    }
}
