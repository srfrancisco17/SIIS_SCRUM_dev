<?php

namespace app\controllers;

use Yii;
use app\models\SprintRequerimientosTareas;
use app\models\SprintRequerimientosTareasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
    public function actionIndex()
    {
        $searchModel = new SprintRequerimientosTareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    public function actionCreate()
    {
        $model = new SprintRequerimientosTareas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tarea_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SprintRequerimientosTareas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tarea_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SprintRequerimientosTareas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        
        $total_tareas = SprintRequerimientosTareas::find()->select('tiempo_desarrollo')->where(['sprint_id'=>$sprint_id])->andWhere(['sprint_requerimientos_tareas.requerimiento_id'=>$requerimiento_id])->joinWith('tarea')->sum('tiempo_desarrollo'); 
        
        SprintRequerimientos::actualizarHorasSprintRequerimientos($sprint_id, $requerimiento_id, $total_tareas);
        
    }
}
