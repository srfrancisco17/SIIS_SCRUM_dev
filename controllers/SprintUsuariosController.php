<?php

namespace app\controllers;

use Yii;
use app\models\SprintUsuarios;
use app\models\SprintUsuariosSearch;
use app\models\SprintRequerimientos;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Usuarios;

/**
 * SprintUsuariosController implements the CRUD actions for SprintUsuarios model.
 */
class SprintUsuariosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','kanban'],
                'rules' => [
                    [
                        'actions' => ['index','view','create','update','delete','kanban'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    /*
                    [
                        'actions' => ['index-scrum-master', 'about','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $validar_tipousuario = [Usuarios::USUARIO_SCRUM_MASTER];
                            return Usuarios::tipoUsuarioArreglo($validar_tipousuario) && Usuarios::estaActivo();
                        }
                    ],
                    
                    */
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
     * Lists all SprintUsuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SprintUsuariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SprintUsuarios model.
     * @param integer $sprint_id
     * @param integer $usuario_id
     * @return mixed
     */
    public function actionView($sprint_id, $usuario_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sprint_id, $usuario_id),
        ]);
    }

    /**
     * Creates a new SprintUsuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SprintUsuarios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'sprint_id' => $model->sprint_id, 'usuario_id' => $model->usuario_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SprintUsuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $sprint_id
     * @param integer $usuario_id
     * @return mixed
     */
    public function actionUpdate($sprint_id, $usuario_id)
    {
        $model = $this->findModel($sprint_id, $usuario_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'sprint_id' => $model->sprint_id, 'usuario_id' => $model->usuario_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SprintUsuarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $sprint_id
     * @param integer $usuario_id
     * @return mixed
     */
    public function actionDelete($sprint_id, $usuario_id)
    {
        $this->findModel($sprint_id, $usuario_id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionKanban($sprint_id){
        
        $consulta = SprintRequerimientos::find()->where(['sprint_id'=>$sprint_id])->andWhere(['usuario_asignado'=>Yii::$app->user->identity->usuario_id])->orderBy(['prioridad' => SORT_ASC])->all();
        
        /*
            $total_tareas_espera = 0;
            $total_tareas_progreso = 0;
            $total_tareas_terminada = 0;
        */
        //return $this->render('kanban');
        
        return $this->render('kanban', [
            'consulta' => $consulta,
        ]);
       
    }
    
    public function actionRespuesta($id, $estado, $sprint_id = false, $requerimiento_id = false){
       //CAMBIOS (*-*)\_
        $model = new \app\models\SprintRequerimientosTareas();
        
        if (Yii::$app->request->isAjax){
            
            //$model->actualizarEstadoTareas($id, $estado);
            
            if ($estado == 2){
                
                $model->actualizarEstadoTareas($id, $estado, 2);
                
                /*
                $sw_var = \app\models\SprintRequerimientosTareas::find()
                ->where(['sprint_id' => $sprint_id])
                ->andWhere(['requerimiento_id' => $requerimiento_id])
                ->andWhere(['estado' => '3'])
                ->count();
                */ 

                $sw_var = \app\models\SprintRequerimientosTareas::find()
                ->where(['between', 'estado','3', '4'])
                ->andWhere(['requerimiento_id' => $requerimiento_id])
                ->andWhere(['sprint_id' => $sprint_id])
                ->count();                
                
                if ($sw_var == 0){

                    \app\models\SprintRequerimientos::actualizarEstadoSprintRequerimientos($sprint_id, $requerimiento_id, '2');
                }
                
            }else if ($estado == 3){
                $model->actualizarEstadoTareas($id, $estado, 3);
                
                
                \app\models\SprintRequerimientos::actualizarEstadoSprintRequerimientos($sprint_id, $requerimiento_id, '3');
            }
            else if ($estado == 4){
                echo 'estoy en el 4';
                
                $model->actualizarEstadoTareas($id, $estado, 4);

                $sw_var = \app\models\SprintRequerimientosTareas::find()
                ->where(['between', 'estado','2', '3'])
                ->andWhere(['requerimiento_id' => $requerimiento_id])
                ->andWhere(['sprint_id' => $sprint_id])
                ->count();
                
                if ($sw_var == 0){
                    
                    echo 'estoy en el bet';
                   \app\models\SprintRequerimientos::actualizarEstadoSprintRequerimientos($sprint_id, $requerimiento_id, '4');
                }
                
            }
            
        } 
    }

    /**
     * Finds the SprintUsuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $sprint_id
     * @param integer $usuario_id
     * @return SprintUsuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sprint_id, $usuario_id)
    {
        if (($model = SprintUsuarios::findOne(['sprint_id' => $sprint_id, 'usuario_id' => $usuario_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
