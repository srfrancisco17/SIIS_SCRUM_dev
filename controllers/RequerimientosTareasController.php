<?php

namespace app\controllers;

use Yii;
use app\models\ValorHelpers;
use app\models\SprintRequerimientos;
use app\models\SprintRequerimientosTareas;
use app\models\RequerimientosTareas;
use app\models\RequerimientosTareasSearch;
use app\models\Requerimientos;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * RequerimientosTareasController implements the CRUD actions for RequerimientosTareas model.
 */
class RequerimientosTareasController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all RequerimientosTareas models.
     * @return mixed
     */
    public function actionIndex($sprint_id = FALSE, $requerimiento_id)// $requerimiento_id
    {
        

        //$requerimiento_id = Yii::$app->request->post('param1', null);
        
        $searchModel = new RequerimientosTareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $requerimiento_id);
        
        $count = 0;
        
        foreach ($dataProvider->getModels() as $variable ){
            
            if ($variable->ultimo_estado == 5){
                $count++;
            }
        }
        
        $modelRequerimiento = Requerimientos::findOne(['requerimiento_id'=>$requerimiento_id]);
        
        if (empty($modelRequerimiento) || $count > 0){
            return $this->redirect(['requerimientos/index']);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelRequerimiento' => $modelRequerimiento,
            'sprint_id' => $sprint_id,
        ]);
    }

    /**
     * Displays a single RequerimientosTareas model.
     * @param integer $id
     * @return mixed
    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RequerimientosTareas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate($sprint_id = FALSE, $requerimiento_id, $submit = false)
    {
        $model = new RequerimientosTareas();
        
        $model_sprintRequerimientosTareas = new SprintRequerimientosTareas();
 
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $model->requerimiento_id = $requerimiento_id;
            $model->ultimo_estado = NULL;
            $model->fecha_terminado = NULL;
            
            $sw_model = $model->save();
           
       
            $model_sprintRequerimientosTareas->tarea_id = $model->tarea_id;
            $model_sprintRequerimientosTareas->requerimiento_id = $requerimiento_id;
            
            if ($sprint_id != FALSE){
                $model_sprintRequerimientosTareas->sprint_id = $sprint_id;
                
            }else{
                $model_sprintRequerimientosTareas->sprint_id = NULL;
            }
            
            if ($sw_model && $model_sprintRequerimientosTareas->save()){
                

                
                Requerimientos::updateTiempoDesarrollo($requerimiento_id);
                
                if ($sprint_id != FALSE){
                    ValorHelpers::actualizarTiempos($sprint_id);
                }
                
                $model->refresh();
                $model_sprintRequerimientosTareas->refresh();
                
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '¡Tarea creada con ÉXITO!',
                ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RequerimientosTareas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($sprint_id = FALSE, $tarea_id, $submit = false)
    {
        $model = $this->findModel($tarea_id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                 
                Requerimientos::updateTiempoDesarrollo($model->requerimiento_id);
                
                if ($sprint_id != FALSE){
                    ValorHelpers::actualizarTiempos($sprint_id);
                }
                
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
     * Deletes an existing RequerimientosTareas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($sprint_id = FALSE, $tarea_id)
    {
        $model_requerimientosTareas = $this->findModel($tarea_id);
        $model_sprintRequerimientosTareas = $this->findModelsprintRequerimientosTareas($tarea_id);
        
        $requerimiento_id = $model_requerimientosTareas->requerimiento_id;
        
        if ($model_sprintRequerimientosTareas != FALSE ){
            $model_sprintRequerimientosTareas->delete();
            
            //self::actualizarTiempoDesarrollo_SprintRequerimientos($sprint_id, $model_requerimientosTareas->requerimiento_id);
        }
        
        $model_requerimientosTareas->delete();
        
        Requerimientos::updateTiempoDesarrollo($model_requerimientosTareas->requerimiento_id);
        
        
        if ($sprint_id != FALSE ){
            
            ValorHelpers::actualizarTiempos($sprint_id);
            return $this->redirect(['sprint-usuarios/kanban', 'sprint_id' => $sprint_id]);
            
        }

        return $this->redirect(['index', 'requerimiento_id' => $requerimiento_id]);
    }

    /**
     * Finds the RequerimientosTareas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RequerimientosTareas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RequerimientosTareas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findModelsprintRequerimientosTareas($id)
    {
        if (($model = SprintRequerimientosTareas::findOne($id)) !== null) {
            return $model;
        } else {
            return FALSE;
        }
    }
    
    public function actualizarTiempoDesarrollo_SprintRequerimientos($sprint_id, $requerimiento_id){
        
        $total_tareas = SprintRequerimientosTareas::find()->select('tiempo_desarrollo')->where(['sprint_id'=>$sprint_id])->andWhere(['sprint_requerimientos_tareas.requerimiento_id'=>$requerimiento_id])->joinWith('tarea')->sum('tiempo_desarrollo'); 
        
        //throw new NotFoundHttpException('total_tareas '.$total_tareas);
        //$total_tareas = 50;
        
        SprintRequerimientos::actualizarHorasSprintRequerimientos($sprint_id, $requerimiento_id, $total_tareas);
        
    }
    

}
