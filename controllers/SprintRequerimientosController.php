<?php

namespace app\controllers;

use Yii;
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
/**
 * SprintRequerimientosController implements the CRUD actions for SprintRequerimientos model.
 */
class SprintRequerimientosController extends Controller
{
    /**
     * @inheritdoc
     */
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
    
    public function actionIndex($sprint_id)
    {
       
        $sprintRequerimientosSearchModel = new SprintRequerimientosSearch();
        $sprintRequerimientosDataProvider = $sprintRequerimientosSearchModel->search(Yii::$app->request->queryParams,$sprint_id, 1);
        $sprintRequerimientosDataProvider->pagination->pageSize=30;
        
        $usuariosSearchModel = new UsuariosSearch();
        $usuariosDataProvider = $usuariosSearchModel->search(Yii::$app->request->queryParams,2,$sprint_id);
        
        $sprintUsuariosSearchModel = new SprintUsuariosSearch();
        $sprintUsuariosDataProvider = $sprintUsuariosSearchModel->search(Yii::$app->request->queryParams,$sprint_id);


        return $this->render('index', [
            'sprintRequerimientosSearchModel' => $sprintRequerimientosSearchModel,
            'sprintRequerimientosDataProvider' => $sprintRequerimientosDataProvider,
            'sprint_id' => $sprint_id,
            //'usuariosSearchModel' =>$usuariosSearchModel,
            'usuariosDataProvider' =>$usuariosDataProvider,
            //'sprintUsuariosSearchModel' =>$sprintUsuariosSearchModel,
            'sprintUsuariosDataProvider' =>$sprintUsuariosDataProvider,
        ]);
    }

    /**
     * Displays a single SprintRequerimientos model.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return mixed
     */
    public function actionView($sprint_id, $requerimiento_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sprint_id, $requerimiento_id),
        ]);
    }

    /**
     * Creates a new SprintRequerimientos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sprint_id, $submit = false)
    {
        $model = new SprintRequerimientos();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            //Cuando se asocia un requerimiento al sprint este pasa de estado (Activo = 1) a (En Espera = 2) 
            Requerimientos::actualizarEstadoRequerimientos($model->requerimiento_id, '2');
            
            if ($model->save()) {
                
                $query = SprintRequerimientosTareas::find()->select('tarea_id, requerimiento_id')->where(['requerimiento_id' => $model->requerimiento_id])->andWhere('sprint_id is NULL')->all();               

                
                if (!empty($query)){                
                    
                    $conexion = Yii::$app->db;
                    
                    foreach ($query as $objTareas) {
                        
                        $command = $conexion->createCommand('UPDATE sprint_requerimientos_tareas SET sprint_id='.$model->sprint_id.' WHERE tarea_id='.$objTareas->tarea_id.' AND requerimiento_id ='.$objTareas->requerimiento_id.' AND sprint_id is null');
                        $command->execute();
                         
                    }
                    
                    //self::actualizarTiempoDesarrollo_SprintRequerimientos($model->sprint_id, $model->requerimiento_id);
                    
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

        return $this->renderAjax('create', [
            'model' => $model,
            'sprint_id' => $sprint_id,
        ]);
    }

    /**
     * Updates an existing SprintRequerimientos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return mixed
     */
    public function actionUpdate($sprint_id, $requerimiento_id, $submit = false)
    {
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
    public function actionDelete($sprint_id, $requerimiento_id)
    {
        $this->findModel($sprint_id, $requerimiento_id)->delete();
        
        /*
        *   Comando Para Cambiar de estado
        */
        
        $query = SprintRequerimientosTareas::find()->select('tarea_id')->where(['requerimiento_id' => $requerimiento_id])->andWhere(['sprint_id' => $sprint_id])->all();
                
            if (!empty($query)){                
                    
                $conexion = Yii::$app->db;
                    
                foreach ($query as $objTareas) {
                        
                    $command = $conexion->createCommand('UPDATE sprint_requerimientos_tareas SET sprint_id = NULL, estado = \'2\', fecha_terminado = NULL WHERE tarea_id='.$objTareas->tarea_id);
                    $command->execute();
                         
                }
            }        
        
        
        Requerimientos::actualizarEstadoRequerimientos($requerimiento_id, '1');

        return $this->redirect(['index','sprint_id'=>$sprint_id]);
    }

    /**
     * Finds the SprintRequerimientos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $sprint_id
     * @param integer $requerimiento_id
     * @return SprintRequerimientos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sprint_id, $requerimiento_id)
    {
        if (($model = SprintRequerimientos::findOne(['sprint_id' => $sprint_id, 'requerimiento_id' => $requerimiento_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPeticion1($id, $k){

        $model = new \app\models\SprintUsuarios();
        
        if (Yii::$app->request->isAjax){
        
            $model->insertarSprintUsuarios($id, $k);

            //$model->refresh();
            //Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->redirect(['index','sprint_id'=>$id]);
        } 
    }
    
    public function actionPeticion2($id, $k){
       //CAMBIOS (*-*)\_
        $model = new \app\models\SprintUsuarios();
        
        print_r($k);
        
        if (Yii::$app->request->isAjax){
        
            $model->eliminarSprintUsuarios($id, $k);

            //$model->refresh();
            //Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->redirect(['index','sprint_id'=>$id]);
        } 
    }
    
    public function actionLists($sprint_id, $usuario_id)
    {
        
        $sql = "Select * From prioridad_sprint_requerimientos where Not prioridad_id In (SELECT CASE WHEN prioridad is NULL THEN 99 ELSE prioridad END  from sprint_requerimientos where sprint_id = ".$sprint_id." and usuario_asignado = ".$usuario_id.")";
        $countPrioridad = \app\models\PrioridadSprintRequerimientos::findBySql($sql)->count();
                
        $prioridades = \app\models\PrioridadSprintRequerimientos::findBySql($sql)->orderBy('prioridad_id DESC')->all();
        
        if($countPrioridad>0){
            foreach($prioridades as $prioridad){
                echo "<option value='".$prioridad->prioridad_id."'>".$prioridad->descripcion."</option>";
            }
        }
        else{
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
                if (empty($out)){
                  $out = [["id"=>"-","name"=>"Advertencia"]];  
                } 
                
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    
    
    public function actualizarTiempoDesarrollo_SprintRequerimientos($sprint_id, $requerimiento_id){
        
        $total_tareas = SprintRequerimientosTareas::find()->select('tiempo_desarrollo')->where(['sprint_id'=>$sprint_id])->andWhere(['sprint_requerimientos_tareas.requerimiento_id'=>$requerimiento_id])->joinWith('tarea')->sum('tiempo_desarrollo'); 
        
        SprintRequerimientos::actualizarHorasSprintRequerimientos($sprint_id, $requerimiento_id, $total_tareas);
        
    }    
}
