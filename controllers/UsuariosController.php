<?php

namespace app\controllers;

use Yii;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;
use yii\widgets\ActiveForm;

class UsuariosController extends Controller
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
     * Lists all Usuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $searchModel->estado = 1;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=15;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Usuarios model.
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
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() 
    { 
        $model = new Usuarios(); 

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())){
            
            $model->contrasena = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('Usuarios')['contrasena']);
            
            if  ($model->save()){
                return $this->redirect(['index']);
            }
            
        }
        else { 
            return $this->render('create', [ 
                'model' => $model, 
            ]); 
        } 
    }
    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$contrasena_old = $model->contrasena;
		

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
			

			
			if ($contrasena_old != $model->contrasena){
				
				$model->contrasena =  Yii::$app->security->generatePasswordHash(Yii::$app->request->post('Usuarios')['contrasena']);
			}
			
			// echo "<pre>"; 
			// var_dump($contrasena_old); 
			// var_dump($model->contrasena); 
			// var_dump(Yii::$app->request->post('Usuarios')['contrasena']); 
			// exit;

            if ($model->save()){
                return $this->redirect(['index']);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Usuarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /*
        $this->findModel($id)->delete();
        */
        $connection = Yii::$app->db;
        
        $connection->createCommand()->update('usuarios', ['estado' => 0], ['usuario_id' => $id])->execute();
        
        return $this->redirect(['index']);
    }
    
    public function actionProfile(){
        
        $model = Yii::$app->user->identity;
        
        
        if ($model->load(Yii::$app->request->post())) {
            
            if (!empty(Yii::$app->request->post('Usuarios')['new_password'])){
                
                $nueva_contrasena = Yii::$app->request->post('Usuarios')['new_password'];
                
                $model->updatePassword($nueva_contrasena);
                
            }
            
            
            if ($model->save()){
                 return $this->redirect(['profile']);
            }
            
           
            
        } else {
            return $this->render('profile', [
                'model' => $model,
            ]);
        }        
        
    }
    
    public function actionRespuesta($id){

        if (Yii::$app->request->isAjax){
            
            $conexion = Yii::$app->db;
            
            $conexion->createCommand()
                        ->update('usuarios', ['skin' => $id], ['usuario_id' => Yii::$app->user->identity->usuario_id])
			->execute();
            
            $session = Yii::$app->getSession();
            $session->set('skin_session', $id);
                    
        } 
    }
    

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
