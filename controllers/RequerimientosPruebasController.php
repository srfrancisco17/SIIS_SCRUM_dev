<?php

namespace app\controllers;

use Yii;
use app\models\RequerimientosPruebas;
use app\models\RequerimientosPruebasSearch;
use app\models\SprintRequerimientosSearch2;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use app\models\TareasPruebas;

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
    
    
    public function actionCreate($sprint_id, $requerimiento_id, $submit = false)
    {
        
        
        $model = new RequerimientosPruebas();
        
        $obj_tareas = \app\models\SprintRequerimientosTareas::find()->where(['sprint_id' => $sprint_id])->andWhere(['requerimiento_id' =>$requerimiento_id])->all();
        
        
        

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
        if($model->load(Yii::$app->request->post()))
        {
            
            

            $model->sprint_id = $sprint_id;
            $model->requerimiento_id = $requerimiento_id;
            $model->usuario_pruebas = Yii::$app->user->identity->usuario_id;
            
            $transaction = Yii::$app->db->beginTransaction();
            try {

                if($model->save())
                {
                    
                    
                    $this->guardarTareasPruebas($model->prueba_id, $_POST['radio_tareas']);
                    $transaction->commit();
                    
                    $model->refresh();
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return[
                        'message' => '<p align=center><b>¡Registro creado exitosamente!</b></p>',
                    ];
                } else{
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                
                var_dump($e);
                exit;
                
            }
             
        }
        return $this->renderAjax('_form',[
           'model'=>$model,
           'obj_tareas' => $obj_tareas
        ]);
    }
    
    /**
     * Updates an existing RequerimientosPruebas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($prueba_id, $submit = false)
    {
        $model = $this->findModel($prueba_id);
        
        $obj_tareas = TareasPruebas::find()->where(['prueba_id' => $prueba_id])->all();
              
        /*
        echo '<pre>';
        var_dump($_POST['radio_tareas']);
        exit;
        */
    
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                
                if ($model->save()) {
                    
                    $this->actualizarTareasPruebas($_POST['radio_tareas']);
                    $transaction->commit();

                    $model->refresh();
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'message' => '<p align=center><b>¡Prueba actualizada exitosamente!</b></p>',
                    ];
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }

            }catch (\Exception $e) {
                $transaction->rollBack();
            }
             
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'obj_tareas' => $obj_tareas,
        ]);
        
    }
    
    protected function guardarTareasPruebas($prueba_id, $datos_tarea){
        
        
        foreach ($datos_tarea as $value) {

           $datos = explode("-", $value);

           $model2 = new TareasPruebas();

           $model2->prueba_id = $prueba_id;
           $model2->tarea_id = $datos[0];
           $model2->estado = $datos[1];
           $model2->save();

       }
         
        return true;
        
    }
    
    
    protected function actualizarTareasPruebas($datos_tarea){


        foreach ($datos_tarea as $value) {

           $datos = explode("-", $value);
           
           $model2 = TareasPruebas::findOne($datos[2]);
           $model2->estado = $datos[1];
           $model2->save();

       }

        return true;
        
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
