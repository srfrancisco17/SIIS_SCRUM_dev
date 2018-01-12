<?php

namespace app\controllers;

use Yii;
use app\models\Requerimientos;
use app\models\RequerimientosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Usuarios;

use yii\web\Response;
use yii\widgets\ActiveForm;


use app\models\RequerimientosTareas;
use app\models\RequerimientosTareasSearch;

use app\models\ProcesosInvolucrados;
use app\models\ProcesosInvolucradosSearch;

use app\models\PerfilesUsuariosImpactados;
use app\models\PerfilesUsuariosImpactadosSearch;

use app\models\ValorHelpers;
use app\models\SprintRequerimientosTareas;

use app\models\HelpersFAOF;



class RequerimientosController extends Controller
{

    public function behaviors()
    {
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
                            $validar_tipousuario = [Usuarios::USUARIO_SCRUM_MASTER || Usuarios::USUARIO_DEVELOPER];
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

    
    /*
     * REQUERIMIENTO
     */

    public function actionIndex()
    {
        
        $searchModel = new RequerimientosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=30;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Requerimientos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    

    
    public function actionUpdate($sprint_id = FALSE, $requerimiento_id)
    {
        
        $model = $this->findModel($requerimiento_id);
        
        $RT_searchModel = new RequerimientosTareasSearch();
        $RT_dataProvider = $RT_searchModel->search(Yii::$app->request->queryParams, $sprint_id, $requerimiento_id);
        
        $PI_searchModel = new ProcesosInvolucradosSearch();
        $PI_dataProvider = $PI_searchModel->search(Yii::$app->request->queryParams, $requerimiento_id);
        
        $PUI_searchModel = new PerfilesUsuariosImpactadosSearch();
        $PUI_dataProvider = $PUI_searchModel->search(Yii::$app->request->queryParams, $requerimiento_id);
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            return $this->redirect(['update', 'sprint_id' => $sprint_id, 'requerimiento_id' => $requerimiento_id]);
            
        } else {
            return $this->render('update', [
                'model' => $model,
                'RT_searchModel' => $RT_searchModel,
                'RT_dataProvider' => $RT_dataProvider,
                'PI_searchModel' => $PI_searchModel,
                'PI_dataProvider' => $PI_dataProvider,
                'PUI_searchModel' => $PUI_searchModel,
                'PUI_dataProvider' => $PUI_dataProvider,
                'sprint_id' => $sprint_id
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->estado = '0';
        
        $model->save();
        
        
        return $this->redirect(['index']);
    }

    /*
     * FIN REQUERIMIENTO
     */
    
    
    /*
     * REQUERIMIENTOS-TAREAS
     */
    
    public function actionCreateRequerimientosTareas($sprint_id = FALSE, $requerimiento_id, $submit = FALSE)
    {
        
        //var_dump($sprint_id);exit;
        
        $model = new RequerimientosTareas();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == FALSE)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
        
        
        if($model->load(Yii::$app->request->post()))
        {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            
            /* BEGIN TRANSACTION */
            $model->requerimiento_id = $requerimiento_id;


            try {
                
                if ($model->save()) {
                    
                    /* Crear registro en sprint-requerimientos-tareas */
                    
                    $model2 = new SprintRequerimientosTareas();
                    $model2->tarea_id = $model->tarea_id;
                    $model2->requerimiento_id = $requerimiento_id;
                    $model2->sprint_id = ($sprint_id == FALSE) ? NULL : $sprint_id;
                    $model2->save();
                    

                    HelpersFAOF::actualizarTiempos($connection, $sprint_id, $requerimiento_id);
                    
                    $transaction->commit();
                    
                    
                    $model->refresh();
                    $model2->refresh();
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['message' => '<p align=center><b>¡Tarea creada exitosamente!</b></p>',];
                    
                    
                } else {
                    
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['message' => '<p align=center><b>¡ERROR al momento de crear la tarea!</b></p>',];
                    
                    //Yii::$app->response->format = Response::FORMAT_JSON;
                    //return ActiveForm::validate($model);
                    //throw Exception('Unable to save record.');
    
                }
                
            } catch(\yii\db\Exception $e) {
                $transaction->rollback();   
            }
            
        }
        
        return $this->renderAjax('form_requerimientos_tareas',[
           'model'=>$model, 
        ]);
    }
    
    
    public function actionUpdateRequerimientosTareas($tarea_id, $sprint_id = FALSE, $submit = FALSE){
        
        /*
        echo '<pre>';
        var_dump($sprint_id);
        exit;
        */
        
        $model = $this->findModelRequerimientosTareas($tarea_id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == FALSE) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
             
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            /* BEGIN TRANSACTION */
            
            try {
                
                if ($model->save()) {

                    HelpersFAOF::actualizarTiempos($connection, $sprint_id, $model->requerimiento_id);
                    $transaction->commit();
                    $model->refresh();

                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'message' => '<p align=center><b>¡Tarea actualizada exitosamente!</b></p>',
                    ];
                    
                } else {
                    
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['message' => '<p align=center><b>¡ERROR al momento de actualizar la tarea!</b></p>',];
                    
                    //Yii::$app->response->format = Response::FORMAT_JSON;
                    //return ActiveForm::validate($model);
                }
                
            } catch(\yii\db\Exception $e) {
                $transaction->rollback();   
            }
            
        }

        return $this->renderAjax('form_requerimientos_tareas', [
            'model' => $model,
        ]);
        
        
    }
    
    public function actionDeleteRequerimientosTareas($tarea_id, $sprint_id = FALSE)
    {

            $model = $this->findModelRequerimientosTareas($tarea_id);
            $requerimiento_id = $model->requerimiento_id;

            if ( !empty($sprint_id) ){

                $model2 = SprintRequerimientosTareas::find()->where(['tarea_id'=> $tarea_id])->andWhere(['requerimiento_id'=>$requerimiento_id])->andWhere(['sprint_id'=> $sprint_id])->one();

                $model2->delete();

            }

            $model->delete();

            /* Actualizar */
            
            $connection = \Yii::$app->db;
            HelpersFAOF::actualizarTiempos($connection, $sprint_id, $requerimiento_id);
            

        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['requerimientos/update', 'requerimiento_id' => $requerimiento_id]);
        }

    }
    
    /*
     * FIN REQUERIMIENTOS-TAREAS
     */    
    
    
    /*
     * PROCESOS INVOLUCRADOS
     */

    public function actionCreateProcesosInvolucrados($requerimiento_id, $submit = false)
    {
        
        $model = new ProcesosInvolucrados();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
        if($model->load(Yii::$app->request->post()))
        {
            
            $model->requerimiento_id = $requerimiento_id;
            
            if($model->save())
            {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return[
                    'message' => '<p align=center><b>¡Proceso involucrado agregado exitosamente!</b></p>',
                ];
            } else{
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->renderAjax('form_procesos_involucrados',[
           'model'=>$model, 
        ]);
    }
    
    public function actionUpdateProcesosInvolucrados($id, $submit = false){
        
        $model = $this->findModelProcesosInvolucrados($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '<p align=center><b>¡Proceso involucrado actualizado exitosamente!</b></p>',
                ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->renderAjax('form_procesos_involucrados', [
            'model' => $model,
        ]);
        
        
    }
    
    public function actionDeleteProcesosInvolucrados($id, $requerimiento_id)
    {
        
        $model = $this->findModelProcesosInvolucrados($id)->delete();
        
        
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['requerimientos/update', 'requerimiento_id' => $requerimiento_id]);
        }

    } 
    
    
    /*
     * FIN PROCESOS INVOLUCRADOS
     */
    
    
    /*
     * PERFILES IMPACTADOS
     */

    public function actionCreatePerfilesImpactados($requerimiento_id, $submit = false)
    {
        
        $model = new PerfilesUsuariosImpactados();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
        if($model->load(Yii::$app->request->post()))
        {
            
            $model->requerimiento_id = $requerimiento_id;
            
            if($model->save())
            {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return[
                    'message' => '<p align=center><b>¡Perfil de usuario impactado agregado exitosamente!</b></p>',
                ];
            } else{
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->renderAjax('form_perfiles_impactados',[
           'model'=>$model, 
        ]);
    }
    
    public function actionUpdatePerfilesImpactados($id, $submit = false){
        
        $model = $this->findModelPerfilesImpactados($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'message' => '<p align=center><b>¡Perfil de usuario impactado actualizado exitosamente!</b></p>',
                ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->renderAjax('form_perfiles_impactados', [
            'model' => $model,
        ]);
        
        
    }
    
    public function actionDeletePerfilesImpactados($id, $requerimiento_id)
    {
        
        $model = $this->findModelPerfilesImpactados($id)->delete();
        
        
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['requerimientos/update', 'requerimiento_id' => $requerimiento_id]);
        }

    }
    
    /*
     * FIN PERFILES IMPACTADOS
     */
    
    /*
     * INICIO METODOS FIND MODEL
     */ 

    protected function findModel($id)
    {
        if (($model = Requerimientos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /* Requerimientos_Tareas */
    protected function findModelRequerimientosTareas($id)
    {
        if (($model = RequerimientosTareas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    /* Procesos Involucrados */
    protected function findModelProcesosInvolucrados($id)
    {
        if (($model = ProcesosInvolucrados::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /* Perfiles Impactados */
    protected function findModelPerfilesImpactados($id)
    {
        if (($model = PerfilesUsuariosImpactados::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
