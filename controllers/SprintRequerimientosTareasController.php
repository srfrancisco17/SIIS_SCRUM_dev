<?php

namespace app\controllers;

use Yii;
use app\models\SprintRequerimientos;
use app\models\SprintRequerimientosTareas;
use app\models\SprintRequerimientosTareasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\widgets\ActiveForm;
/**
 * SprintRequerimientosTareasController implements the CRUD actions for SprintRequerimientosTareas model.
 */
class SprintRequerimientosTareasController extends Controller
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
     * Lists all SprintRequerimientosTareas models.
     * @return mixed
     */
    public function actionIndex($sprint_id, $requerimiento_id)
    {
        $searchModel = new SprintRequerimientosTareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $sprint_id, $requerimiento_id);
        
        $requerimiento = \app\models\Requerimientos::findOne(['requerimiento_id'=>$requerimiento_id]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sprint_id' =>$sprint_id, 
            'requerimiento_id' => $requerimiento_id,
            'requerimiento' => $requerimiento,
        ]);
    }

    /**
     * Displays a single SprintRequerimientosTareas model.
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
     * Creates a new SprintRequerimientosTareas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sprint_id, $requerimiento_id, $submit = false)
    {
        $model = new SprintRequerimientosTareas();

       
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->sprint_id = $sprint_id;
            $model->requerimiento_id = $requerimiento_id;

            
            if ($model->save()) {
                
                self::actualizarTiempoDesarrollo_SprintRequerimientos($model->sprint_id, $model->requerimiento_id);

                
                $model->refresh();
                /*
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '¡Éxito!',
                ];
                */
                return TRUE;
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
     * Updates an existing SprintRequerimientosTareas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $submit = false)
    {
        $model = $this->findModel($id);
        /*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tarea_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        } 
        */
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                
                
                self::actualizarTiempoDesarrollo_SprintRequerimientos($model->sprint_id, $model->requerimiento_id);
                
                $model->refresh();
                /*
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '¡Éxito!',
                ]; 
                */
                return TRUE;
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
     * Deletes an existing SprintRequerimientosTareas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $sprint_id, $requerimiento_id)
    {
        
        $model = $this->findModel($id);
        
        $model->delete();
           
        self::actualizarTiempoDesarrollo_SprintRequerimientos($model->sprint_id, $model->requerimiento_id);

        return $this->redirect(['index', 'sprint_id' => $sprint_id, 'requerimiento_id' => $requerimiento_id]);
    }

    /**
     * Finds the SprintRequerimientosTareas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SprintRequerimientosTareas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SprintRequerimientosTareas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actualizarTiempoDesarrollo_SprintRequerimientos($sprint_id, $requerimiento_id){
        
        $total_tareas = SprintRequerimientosTareas::find()->select('tiempo_desarrollo')->where(['sprint_id'=>$sprint_id])->andWhere(['requerimiento_id'=>$requerimiento_id])->sum('tiempo_desarrollo'); 
        
        SprintRequerimientos::actualizarHorasSprintRequerimientos($sprint_id, $requerimiento_id, $total_tareas);
        
    }
    
    
}
