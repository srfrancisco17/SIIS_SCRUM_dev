<?php

namespace app\controllers;

use Yii;
use app\models\ValorHelpers;
use app\models\Requerimientos;
use app\models\SprintRequerimientos;
use app\models\SprintRequerimientosTareas;
use app\models\SprintUsuariosSearch;
use app\models\UsuariosSearch;
use app\models\SprintRequerimientosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Usuarios;
use yii\filters\AccessControl;
use yii\helpers\Json;
use app\models\HelpersFAOF;
//MPDF
use Mpdf\Mpdf;

/**
 * SprintRequerimientosController implements the CRUD actions for SprintRequerimientos model.
 */
class SprintRequerimientosController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $validar_tipousuario = [Usuarios::USUARIO_SCRUM_MASTER];
                            return Usuarios::tipoUsuarioArreglo($validar_tipousuario) && Usuarios::estaActivo();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SprintRequerimientos models.
     * @return mixed
     */
    public function actionIndex($sprint_id) {

        $sprintRequerimientosSearchModel = new SprintRequerimientosSearch();
        $sprintRequerimientosDataProvider = $sprintRequerimientosSearchModel->search(Yii::$app->request->queryParams, $sprint_id, 1);
        $sprintRequerimientosDataProvider->pagination->pageSize = 30;

        $usuariosSearchModel = new UsuariosSearch();
        $usuariosDataProvider = $usuariosSearchModel->search(Yii::$app->request->queryParams, 2, $sprint_id);

        $sprintUsuariosSearchModel = new SprintUsuariosSearch();
        $sprintUsuariosDataProvider = $sprintUsuariosSearchModel->search(Yii::$app->request->queryParams, $sprint_id);


        return $this->render('index', [
                    'sprintRequerimientosSearchModel' => $sprintRequerimientosSearchModel,
                    'sprintRequerimientosDataProvider' => $sprintRequerimientosDataProvider,
                    'sprint_id' => $sprint_id,
                    //'usuariosSearchModel' =>$usuariosSearchModel,
                    'usuariosDataProvider' => $usuariosDataProvider,
                    //'sprintUsuariosSearchModel' =>$sprintUsuariosSearchModel,
                    'sprintUsuariosDataProvider' => $sprintUsuariosDataProvider,
        ]);
    }

    /**
     * Displays a single SprintRequerimientos model.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return mixed
     */
    public function actionView($sprint_id, $requerimiento_id) {
        return $this->render('view', [
                    'model' => $this->findModel($sprint_id, $requerimiento_id),
        ]);
    }

    /**
     * Creates a new SprintRequerimientos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sprint_id) {

        $model = new SprintRequerimientos();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {


            $db = Yii::$app->db;

            $transaction = $db->beginTransaction();

            try {
                $insert_sprintRequerimientos_sql = "

                        INSERT
                            INTO
                                sprint_requerimientos(
                                    sprint_id,
                                    requerimiento_id,
                                    usuario_asignado,
                                    prioridad
                                )
                            VALUES(
                                " . $model->sprint_id . ",
                                " . $model->requerimiento_id . ",
                                " . $model->usuario_asignado . ",
                                " . $model->prioridad . "
                            );
                    ";

                $tareasNoTerminadas_sql = "
                        SELECT
                            tarea_id,
                            requerimiento_id,
                            tarea_titulo,
                            tarea_descripcion,
                            ultimo_estado,
                            horas_desarrollo,
                            fecha_terminado
                        FROM
                            requerimientos_tareas
                        WHERE
                            requerimiento_id = " . $model->requerimiento_id . "
                        AND
                            ultimo_estado = '5';
                    ";

                $db->createCommand($insert_sprintRequerimientos_sql)->execute();

                $result_tareasNoTerminadas = $db->createCommand($tareasNoTerminadas_sql)->queryAll();

                foreach ($result_tareasNoTerminadas as $value_tareasNoTerminadas) {

                    $db->createCommand()->insert('sprint_requerimientos_tareas', [
                        'tarea_id' => $value_tareasNoTerminadas['tarea_id'],
                        'sprint_id' => $model->sprint_id,
                        'requerimiento_id' => $model->requerimiento_id,
                        'estado' => '2',
                    ])->execute();
                }
                //Cuando se asocia un requerimiento al sprint este pasa de estado (Activo = 1) a (En Espera = 2)

                HelpersFAOF::actualizarTiempos($db, $model->sprint_id, $model->requerimiento_id);

                Requerimientos::updateRequerimientos($db, $model->requerimiento_id, '2');

                $model->refresh();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {

            return $this->renderAjax('create', [
                        'model' => $model,
                        'sprint_id' => $sprint_id,
            ]);
        }
    }

    /**
     * Updates an existing SprintRequerimientos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return mixed
     */
    public function actionUpdate($sprint_id, $requerimiento_id, $submit = false) {
        $model = $this->findModel($sprint_id, $requerimiento_id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '¡Éxito!',
                ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->renderAjax('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SprintRequerimientos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return mixed
     */
    public function actionDelete($sprint_id, $requerimiento_id) {
		
		
        $this->findModel($sprint_id, $requerimiento_id)->delete();

        /*
         *   Comando Para Cambiar de estado
         */
        $conexion = Yii::$app->db;

        $query = SprintRequerimientosTareas::find()->select('tarea_id')->where(['requerimiento_id' => $requerimiento_id])->andWhere(['sprint_id' => $sprint_id])->all();

        if (!empty($query)) {

            foreach ($query as $objTareas) {

                $command = $conexion->createCommand('UPDATE sprint_requerimientos_tareas SET sprint_id = NULL, estado = \'2\' WHERE tarea_id=' . $objTareas->tarea_id);
                $command->execute();
            }
        }

        //Actualizacion del tiempo en los sprints
        ValorHelpers::actualizarTiempos($sprint_id);

        Requerimientos::updateRequerimientos($conexion, $requerimiento_id, '1');

        return $this->redirect(['index', 'sprint_id' => $sprint_id]);
    }

    /**
     * Finds the SprintRequerimientos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return SprintRequerimientos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sprint_id, $requerimiento_id) {
        if (($model = SprintRequerimientos::findOne(['sprint_id' => $sprint_id, 'requerimiento_id' => $requerimiento_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function getNombreRequerimientoSoporte($usuario_id){

		setlocale(LC_TIME, 'spanish');  
		
		return "SOP-".$usuario_id." ".strftime("%B",mktime(0, 0, 0, date('n'), 1, 2000))." ".date('Y'); 		
		
	}

    public function actionPeticion1() {

        $request = Yii::$app->request;

        $sprint_id = $request->post("sprint_id");
        $lista_usuarios = $request->post("lista_usuarios");


        $dbconn = Yii::$app->db;
        $transaction = $dbconn->beginTransaction();

        try {

            foreach ($lista_usuarios as $usuario_id => $horas) {

                if (!empty($sprint_id) && !empty($usuario_id) && !empty($horas)) {


					$exist_sprint_usuarios = $dbconn->createCommand("SELECT COUNT(*) FROM sprint_usuarios WHERE sprint_id = ".$sprint_id." AND usuario_id = ".$usuario_id.";")->queryScalar();


					if ($exist_sprint_usuarios == '0'){
							
						$dbconn->createCommand()->insert('sprint_usuarios', [
							'sprint_id' => $sprint_id,
							'usuario_id' => $usuario_id,
							'horas_establecidas' => $horas,
							'estado' => '1'
						])->execute();
						
					}else if ($exist_sprint_usuarios == '1'){
						
						
						$dbconn->createCommand()->update('sprint_usuarios', [
							'horas_establecidas' => $horas,
							'estado' => '1'
						], ['sprint_id' => $sprint_id, 'usuario_id' => $usuario_id])->execute();
						
					}
					

					$sw_generar_soportes = $dbconn->createCommand("SELECT sw_generar_soportes FROM sprints WHERE sprint_id = ".$sprint_id.";")->queryScalar();
					
					
					if ($sw_generar_soportes == '1'){


						$exist_req_soporte = $dbconn->createCommand("
							SELECT
								COUNT(*)
							FROM
								sprint_requerimientos AS SR
							INNER JOIN requerimientos AS R ON(
								R.requerimiento_id = SR.requerimiento_id
							)
							WHERE
								SR.sprint_id = ".$sprint_id."
							AND SR.usuario_asignado = ".$usuario_id."
							AND R.sw_soporte = '1';
						")->queryScalar();
						
						
						if ($exist_req_soporte == '0'){
							
	
							$dbconn->createCommand()->insert('requerimientos', [
								'requerimiento_titulo' => $this->getNombreRequerimientoSoporte($usuario_id),
								'usuario_solicita' => 1,
								'fecha_requerimiento' => date('Y-m-d'),
								'estado' => '2',
								'sw_soporte' => '1'
							])->execute();
							
							
							$requerimiento_id = $dbconn->getLastInsertID();
							
							
							if (!empty($requerimiento_id)){
								
								$dbconn->createCommand()->insert('sprint_requerimientos', [
									'sprint_id' => $sprint_id,
									'requerimiento_id' => $requerimiento_id,
									'usuario_asignado' => $usuario_id,
									'tiempo_desarrollo' => 0,
									'prioridad' => 20
								])->execute();
								
								
							}

						}
					}
                }
            }

            $transaction->commit();
			
        } catch (\yii\db\Exception $e) {
			
            $transaction->rollback();
			return false;
        }
		
		
		return true;

		
    }

    public function actionPeticion2($id, $k) {
        //CAMBIOS (*-*)\_
        $model = new \app\models\SprintUsuarios();

        print_r($k);

        if (Yii::$app->request->isAjax) {

            $model->eliminarSprintUsuarios($id, $k);

            //$model->refresh();
            //Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->redirect(['index', 'sprint_id' => $id]);
        }
    }

    public function actionLists($sprint_id, $usuario_id) {

        $sql = "Select * From prioridad_sprint_requerimientos where Not prioridad_id In (SELECT CASE WHEN prioridad is NULL THEN 99 ELSE prioridad END  from sprint_requerimientos where sprint_id = " . $sprint_id . " and usuario_asignado = " . $usuario_id . ")";
        $countPrioridad = \app\models\PrioridadSprintRequerimientos::findBySql($sql)->count();

        $prioridades = \app\models\PrioridadSprintRequerimientos::findBySql($sql)->orderBy('prioridad_id DESC')->all();

        if ($countPrioridad > 0) {
            foreach ($prioridades as $prioridad) {
                echo "<option value='" . $prioridad->prioridad_id . "'>" . $prioridad->descripcion . "</option>";
            }
        } else {
            echo "<option>-</option>";
        }
    }

    public function actionSubcat($sprint_id) {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $cat_id = $parents[0];

                //print_r($parents);

                $out = \app\models\PrioridadSprintRequerimientos::getSubCatList($sprint_id, $cat_id);

                //if ($out )
                if (empty($out)) {
                    $out = [["id" => "-", "name" => "Advertencia"]];
                }

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }

        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionPrintHistoriaUsuario($sprint_id, $requerimiento_id) {

        /*         * Pag 1 * */

        $datos_tareas = $this->obtenerTareasPruebas($sprint_id, $requerimiento_id);

        $obj_requerimiento = SprintRequerimientos::find()->where(['sprint_id' => $sprint_id])->andWhere(['requerimiento_id' => $requerimiento_id])->one();
        $obj_procesos_involucrados = \app\models\ProcesosInvolucrados::find()->where(['requerimiento_id' => $requerimiento_id])->limit(9)->asArray()->all();
        $obj_perfiles_impactados = \app\models\PerfilesUsuariosImpactados::find()->where(['requerimiento_id' => $requerimiento_id])->limit(9)->asArray()->all();

        /*         * Pag 2 * */

        $obj_pruebas = \app\models\RequerimientosPruebas::find()->where(['requerimiento_id' => $requerimiento_id])->all();

        $obj_requerimientos_implementacion = \app\models\RequerimientosImplementacion::findOne($requerimiento_id);

        $limite_texto = 280;


        $obj_requerimiento->requerimiento->requerimiento_descripcion = (
                strlen($obj_requerimiento->requerimiento->requerimiento_descripcion) > $limite_texto ? substr($obj_requerimiento->requerimiento->requerimiento_descripcion, 0, $limite_texto) . "..." : $obj_requerimiento->requerimiento->requerimiento_descripcion
                );

        $obj_requerimiento->requerimiento->requerimiento_funcionalidad = (
                strlen($obj_requerimiento->requerimiento->requerimiento_funcionalidad) > $limite_texto ? substr($obj_requerimiento->requerimiento->requerimiento_funcionalidad, 0, $limite_texto) . "..." : $obj_requerimiento->requerimiento->requerimiento_funcionalidad
                );

        $obj_requerimiento->requerimiento->requerimiento_justificacion = (
                strlen($obj_requerimiento->requerimiento->requerimiento_justificacion) > $limite_texto ? substr($obj_requerimiento->requerimiento->requerimiento_justificacion, 0, $limite_texto) . "..." : $obj_requerimiento->requerimiento->requerimiento_justificacion
                );

        /*
         * VALIDAR QUE LOS DATOS DEL REPORTE SE HALLAN CARGADO CORRECTAMENTE
         */

        $content1 = $this->renderPartial('_reportHU_pag1', [
            'sprint_id' => $sprint_id,
            'obj_requerimiento' => $obj_requerimiento,
            'datos_tareas' => $datos_tareas,
            'obj_procesos_involucrados' => $obj_procesos_involucrados,
            'obj_perfiles_impactados' => $obj_perfiles_impactados,
        ]);

        $content2 = $this->renderPartial('_reportHU_pag2', [
            'sprint_id' => $sprint_id,
            'obj_pruebas' => $obj_pruebas,
            'obj_requerimientos_implementacion' => $obj_requerimientos_implementacion,
        ]);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'orientation' => 'P'
        ]);

        $mpdf->useActiveForms = TRUE;
        // Document Metadata
        $mpdf->SetTitle("HU-" . $requerimiento_id . "(" . date('Y-m-d') . ")");
        $mpdf->SetAuthor('Desarrollo8');
        $mpdf->SetCreator('FAOF');
        $mpdf->SetSubject("Historias de usuario CDO CALI");
        $mpdf->SetKeywords('HU, CDO, SIIS, FAOF');


        // Encryption & Passwords
        //$mpdf->SetProtection(array('copy','print'), 'ñ2018', '123456');


        $mpdf->SetHeader('HISTORIA DE USUARIO ' . $requerimiento_id);
        $mpdf->SetFooter('Pagina # {PAGENO}');




        $mpdf->WriteHTML($content1);

        // Two PAGE
        $mpdf->AddPage();

        $mpdf->WriteHTML($content2);


        // return the pdf output as per the destination setting
        $mpdf->Output();
        exit;
    }

    protected function obtenerTareasPruebas($sprint_id, $requerimiento_id) {

        $connection = Yii::$app->db;

        $query = "
            SELECT
                SRT.tarea_id,
                SRT.sprint_id,
                SRT.requerimiento_id,
                SRT.estado,
                    RT.tarea_id,
                    RT.requerimiento_id,
                    RT.tarea_titulo,
                    RT.tarea_descripcion,
                    RT.ultimo_estado,
                    RT.horas_desarrollo,
                    RT.fecha_terminado,
                    RT.sw_urgente
            FROM
                sprint_requerimientos_tareas AS SRT
            INNER JOIN requerimientos_tareas AS RT ON(
                RT.tarea_id = SRT.tarea_id
            )
            WHERE SRT.sprint_id = " . $sprint_id . " AND SRT.requerimiento_id = " . $requerimiento_id . "
            ORDER BY SRT.tarea_id ASC;
        ";

        $datos1 = $connection->createCommand($query)->queryAll();

        $datos2 = array();
        $i = 0;

        foreach ($datos1 as $key => $value) {

            $datos2[$i] = $value;

            $datos2[$i]['tareas_pruebas'] = $connection->createCommand("SELECT * FROM tareas_pruebas WHERE tarea_id = " . $value['tarea_id'] . " ORDER BY id DESC LIMIT 3")->queryAll();

            $i++;
        }

        //echo '<pre>';print_r($datos2);exit;

        return $datos2;
    }

    public function verificarRequerimientoSoporte($sprint_id, $usuario_asignado, $connection) {


        $obj_sprint = \app\models\Sprints::findOne($sprint_id);
		
        if ($obj_sprint->sw_generar_soportes == '1'){

            $count_soporte = $connection->createCommand("
                SELECT
                    COUNT(*)
                FROM
                    sprint_requerimientos AS SR
                INNER JOIN requerimientos AS R ON(
                    R.requerimiento_id = SR.requerimiento_id
                )
                WHERE
                    SR.sprint_id = " . $sprint_id . "
                AND SR.usuario_asignado = " . $usuario_asignado . "
                AND R.sw_soporte = '1'
            ")->queryScalar();

			// echo "<pre>"; var_dump($count_soporte === '0'); exit;

            if ($count_soporte == '0') {


                $model1 = new Requerimientos();

                $model1->requerimiento_titulo = 'SOPORTE ' . $obj_sprint->sprint_alias;
                $model1->usuario_solicita = 1;
                $model1->fecha_requerimiento = date("Y-m-d");
                $model1->estado = '2';
                $model1->sw_soporte = '1';
				
				
				

                if ($model1->save(false)) {

                    $model2 = new SprintRequerimientos();

                    $model2->sprint_id = $sprint_id;
                    $model2->requerimiento_id = $model1->requerimiento_id;
                    $model2->usuario_asignado = $usuario_asignado;
                    $model2->tiempo_desarrollo = 0;
                    $model2->prioridad = 20;

                    $model2->save();
					
					// echo "<pre>"; var_dump($model2->save()); exit;
					
					
                }
            }
        }
    }

}
