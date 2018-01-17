<?php

namespace app\controllers;

use Yii;
use app\models\SprintUsuarios;
use app\models\Sprints;
use app\models\SprintRequerimientos;
use app\models\SprintsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Usuarios;
use yii\filters\AccessControl;
/**
 * SprintsController implements the CRUD actions for Sprints model.
 */
class SprintsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete','master-kanban'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'master-kanban'],
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
     * Lists all Sprints models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SprintsSearch();
        $searchModel->estado = '1'; 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sprints model.
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
     * Creates a new Sprints model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submit = false)
    {
        $model = new Sprints();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $submit == false)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
        if($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                $model->refresh();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return[
                    'message' => '¡Exito!',
                ];
            } else{
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->renderAjax('create',[
           'model'=>$model, 
        ]);
    }

    /**
     * Updates an existing Sprints model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $submit = false)
    {
        $model = $this->findModel($id);

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
     * Deletes an existing Sprints model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        /*
         * Cambio al momento de eliminar un sprint
         * Intetara eliminarlo si lanza una excepcion, actualiza el estado a 0 = Inactivo
         */
        
        try {
            $model->delete();
        } catch (\yii\db\IntegrityException $e) {
            $model->estado = '0';
            $model->save(); 
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Sprints model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sprints the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sprints::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionMasterKanban($sprint_id){
        
        
        $consulta = SprintRequerimientos::find()->where(['sprint_id'=>$sprint_id])->orderBy(['usuario_asignado'=>SORT_DESC, 'prioridad' => SORT_ASC])->all();
        
        $consulta_usuarios = SprintUsuarios::find()->where(['sprint_id'=>$sprint_id])->orderBy(['usuario_id'=>SORT_DESC])->all();
        
        return $this->render('master_kanban', [
            'consulta' => $consulta,
            'consulta_usuarios' => $consulta_usuarios,
            'sprint_id' => $sprint_id,
        ]);
       
    }
    
    public function actionTerminarSprint($sprint_id){
        
        Sprints::terminarSprint($sprint_id);
        
        return $this->redirect(['sprints/index']);
    }
}
