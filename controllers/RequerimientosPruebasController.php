<?php

namespace app\controllers;

use Yii;
use app\models\RequerimientosPruebas;
use app\models\RequerimientosPruebasSearch;
use app\models\SprintRequerimientosSearch2;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * RequerimientosPruebasController implements the CRUD actions for RequerimientosPruebas model.
 */
class RequerimientosPruebasController extends Controller
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
     * Lists all RequerimientosPruebas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SprintRequerimientosSearch2();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=30;
        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequerimientosPruebas model.
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
     * Creates a new RequerimientosPruebas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    
    public function actionCreate($requerimiento_id, $submit = false)
    {
        
        $model = new RequerimientosPruebas();
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
        if($model->load(Yii::$app->request->post()))
        {
            
            $model->requerimiento_id = $requerimiento_id;
            $model->usuario_pruebas = Yii::$app->user->identity->usuario_id;
            
            if($model->save())
            {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return[
                    'message' => '<p align=center><b>¡Registro creado exitosamente!</b></p>',
                ];
            } else{
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->renderAjax('_form',[
           'model'=>$model, 
        ]);
    }
    
    
    /*
    public function actionCreate()
    {
        $model = new RequerimientosPruebas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->prueba_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    */
    /**
     * Updates an existing RequerimientosPruebas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($prueba_id, $submit = false)
    {
        $model = $this->findModel($prueba_id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '<p align=center><b>¡Prueba actualizada exitosamente!</b></p>',
                ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
        
    }

    /**
     * Deletes an existing RequerimientosPruebas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($prueba_id, $requerimiento_id)
    {
        $this->findModel($prueba_id)->delete();
        
        
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['requerimientos/update', 'requerimiento_id' => $requerimiento_id]);
        }
      
    }

    /**
     * Finds the RequerimientosPruebas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RequerimientosPruebas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RequerimientosPruebas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
