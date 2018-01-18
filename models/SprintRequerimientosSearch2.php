<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SprintRequerimientos;

/**
 * SprintRequerimientosSearch represents the model behind the search form of `app\models\SprintRequerimientos`.
 */
class SprintRequerimientosSearch2 extends SprintRequerimientos
{
    
    
    public $requerimiento;
    public $nombre_usuario_asignado;
    public $requerimiento_titulo;
    public $fecha_requerimiento;
    public $sprint_alias;
    
    public $requerimiento_tiempo_desarrollo;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sprint_id', 'requerimiento_id', 'usuario_asignado', 'tiempo_desarrollo'], 'integer'],
            [['estado', 'requerimiento', 'fecha_requerimiento', 'requerimiento_titulo', 'nombre_usuario_asignado', 'sprint_alias'], 'safe'],
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

        $query = SprintRequerimientos::find()->orderBy(['usuario_asignado' => SORT_DESC, 'prioridad'=>SORT_ASC]);
           
        
        $query->joinWith(['requerimiento', 'usuarioAsignado', 'sprint']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        
        $dataProvider->sort->attributes['requerimiento_titulo'] = [
            'asc' => ['requerimientos.requerimiento_titulo' => SORT_ASC],
            'desc' => ['requerimientos.requerimiento_titulo' => SORT_DESC],
        ];
        
        
        $dataProvider->sort->attributes['nombre_usuario_asignado'] = [
            'asc' => ['usuarios.nombres' => SORT_ASC],
            'desc' => ['usuarios.nombres' => SORT_DESC],
        ];
        
        
        $dataProvider->sort->attributes['fecha_requerimiento'] = [
            'asc' => ['requerimientos.fecha_requerimiento' => SORT_ASC],
            'desc' => ['requerimientos.fecha_requerimiento' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['sprint_alias'] = [
            'asc' => ['sprints.sprint_alias' => SORT_ASC],
            'desc' => ['sprints.sprint_alias' => SORT_DESC],
        ];
        
        
        
        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'sprint_requerimientos.sprint_id' => $this->sprint_id,
            'sprint_requerimientos.requerimiento_id' => $this->requerimiento_id,
            'sprint_requerimientos.usuario_asignado' => $this->usuario_asignado,
            'sprint_requerimientos.tiempo_desarrollo' => $this->tiempo_desarrollo,
            'requerimientos.fecha_requerimiento' => $this->fecha_requerimiento,
            //'requerimientos.requerimiento_tiempo_desarrollo' => $this->requerimiento_tiempo_desarrollo,
        ]);

        $query->andFilterWhere(['ilike', 'sprint_requerimientos.estado', $this->estado]);
        $query->andFilterWhere(['ilike', 'requerimientos.requerimiento_titulo', $this->requerimiento]);
        $query->andFilterWhere(['ilike', 'sprints.sprint_alias', $this->sprint_alias]);
        

        return $dataProvider;
    }
}
