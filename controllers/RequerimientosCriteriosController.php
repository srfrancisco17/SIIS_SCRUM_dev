<?php

namespace app\controllers;

use Yii;
use app\models\RequerimientosCriterios;
use app\models\RequerimientosCriteriosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Requerimientos;

/**
 * RequerimientosCriteriosController implements the CRUD actions for RequerimientosCriterios model.
 */
class RequerimientosCriteriosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new RequerimientosCriteriosSearch();
		$searchModel->estado = '1';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$dbconn = Yii::$app->db;
		
		
		$criterios = $dbconn->createCommand("
			SELECT
				criterio_id,
				descripcion,
				descripcion_abreviada,
				estado,
				orden,
				valor
			FROM
				criterios
			WHERE
				estado = '1'
			ORDER BY
				orden ASC
		")
		->queryAll();
		

		$requerimientos = $dbconn->createCommand("
			SELECT
				R.requerimiento_id,
				R.comite_id,
				R.requerimiento_titulo,
				R.requerimiento_descripcion,
				R.requerimiento_justificacion,
				R.usuario_solicita,
				R.departamento_solicita,
				R.observaciones,
				R.fecha_requerimiento,
				R.estado,
				R.tiempo_desarrollo,
				R.sw_soporte,
				R.requerimiento_funcionalidad,
				R.soporte_id,
				R.divulgacion,
				R.valor_total,
				ERS.descripcion AS estado
			FROM
				requerimientos AS R
			INNER JOIN estados_req_spr AS ERS ON
			( 
				ERS.req_spr_id = R.estado 
			)
			WHERE
				R.estado IN ('0','1','2','3')
			ORDER BY
				R.valor_total DESC NULLS LAST,
				R.fecha_requerimiento ASC,
				R.requerimiento_id
		")
		->queryAll();
		
	
		foreach ($requerimientos as $indice => $requerimiento){
			
			$requerimientos[$indice]["criterios"] = $dbconn->createCommand("
				SELECT
					C.criterio_id,
					C.descripcion,
					C.descripcion_abreviada,
					C.estado,
					C.orden,
					C.valor AS criterio_valor,
					RC.valor AS requerimiento_valor
				FROM
					criterios AS C
				LEFT JOIN requerimientos_criterios AS RC ON
				(
					RC.criterio_id = C.criterio_id AND
					RC.requerimiento_id = ".$requerimiento['requerimiento_id']."
				)
				WHERE
					C.estado = '1'
				ORDER BY
					orden ASC
			")
			->queryAll();
			
			
		}	

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'requerimientos' => $requerimientos,
			'criterios' => $criterios 
        ]);
    }

	
	
    public function actionSave()
    {

		$requerimiento_id = $_POST['requerimiento_id'];
		$criterios = json_decode($_POST['criterios']);
		$valor_total = $_POST['valor_total'];
		
		if (!empty($requerimiento_id) && !empty($criterios)){
			
			foreach ($criterios as $clave => $valor){
				
				$criterio_id = str_replace("c", "", $clave);
				
				$requerimiento_criterios = RequerimientosCriterios::findOne(['requerimiento_id' => $requerimiento_id, 'criterio_id' => $criterio_id]);
				$requerimiento = Requerimientos::findOne(['requerimiento_id' => $requerimiento_id]);


				if (is_null($requerimiento_criterios)){
					
					$requerimiento_criterios = new RequerimientosCriterios();
					$requerimiento_criterios->requerimiento_id = $requerimiento_id;
					$requerimiento_criterios->criterio_id = $criterio_id;
					
				}
				
				if ( $valor_total != "" ){
					
					$requerimiento->valor_total = $valor_total;
					
				}else{
					
					$requerimiento->valor_total = NULL;
					
				}
				
				$requerimiento_criterios->valor = $valor;
				
				$requerimiento_criterios->save();
				$requerimiento->save(false);
				
			}
		}
    }
}
