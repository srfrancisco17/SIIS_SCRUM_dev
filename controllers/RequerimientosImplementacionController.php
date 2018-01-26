<?php

namespace app\controllers;

use Yii;
use app\models\RequerimientosImplementacion;
use app\models\RequerimientosImplementacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequerimientosImplementacionController implements the CRUD actions for RequerimientosImplementacion model.
 */
class RequerimientosImplementacionController extends Controller
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
     * Lists all RequerimientosImplementacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequerimientosImplementacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequerimientosImplementacion model.
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
     * Creates a new RequerimientosImplementacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sprint_id, $requerimiento_id)
            
    {
        
        /*
        echo '<pre>';
        var_dump($requerimiento_id);
        exit;
        */
        
        $model = new RequerimientosImplementacion();
        

        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            

            
            return $this->redirect(['requerimientos/update', 'sprint_id' => $sprint_id , 'requerimiento_id' => $requerimiento_id]);
            

        } else {
            /*
            return $this->render('create', [
                'model' => $model,
            ]);
             * 
             */
        }
    }

    /**
     * Updates an existing RequerimientosImplementacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($sprint_id, $requerimiento_id)
    {
        $model = $this->findModel($requerimiento_id);
        
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            
            return $this->redirect(['requerimientos/update', 'sprint_id' => $sprint_id , 'requerimiento_id' => $requerimiento_id]);
            
        } 
    }

    /**
     * Deletes an existing RequerimientosImplementacion model.
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
     * Finds the RequerimientosImplementacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RequerimientosImplementacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RequerimientosImplementacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
