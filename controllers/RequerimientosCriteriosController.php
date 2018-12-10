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

    /**
     * Lists all RequerimientosCriterios models.
     * @return mixed
     */
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
				R.estado IN ('0','1')
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

    /**
     * Displays a single RequerimientosCriterios model.
     * @param integer $requerimiento_id
     * @param integer $criterio_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($requerimiento_id, $criterio_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($requerimiento_id, $criterio_id),
        ]);
    }

    /**
     * Creates a new RequerimientosCriterios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RequerimientosCriterios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'requerimiento_id' => $model->requerimiento_id, 'criterio_id' => $model->criterio_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RequerimientosCriterios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $requerimiento_id
     * @param integer $criterio_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($requerimiento_id, $criterio_id)
    {
        $model = $this->findModel($requerimiento_id, $criterio_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'requerimiento_id' => $model->requerimiento_id, 'criterio_id' => $model->criterio_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RequerimientosCriterios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $requerimiento_id
     * @param integer $criterio_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($requerimiento_id, $criterio_id)
    {
        $this->findModel($requerimiento_id, $criterio_id)->delete();

        return $this->redirect(['index']);
    }
	


    /**
     * Finds the RequerimientosCriterios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $requerimiento_id
     * @param integer $criterio_id
     * @return RequerimientosCriterios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($requerimiento_id, $criterio_id)
    {
        if (($model = RequerimientosCriterios::findOne(['requerimiento_id' => $requerimiento_id, 'criterio_id' => $criterio_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
    public function actionSave()
    {

		$requerimiento_id = $_POST['requerimiento_id'];
		$criterios = json_decode($_POST['criterios']);
		$valor_total = $_POST['valor_total'];
		
		// echo "<pre>"; var_dump($requerimiento); exit;
		
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
				
				// echo "<pre>"; var_dump($requerimiento->save()); exit;
				
				$requerimiento_criterios->save();
				$requerimiento->save(false);
				
			}
		}
    }
}
