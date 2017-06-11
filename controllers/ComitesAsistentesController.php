<?php

namespace app\controllers;

use Yii;
use app\models\ComitesAsistentes;
use app\models\ComitesAsistentesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Usuarios;
/**
 * ComitesAsistentesController implements the CRUD actions for ComitesAsistentes model.
 */
class ComitesAsistentesController extends Controller
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
     * Lists all ComitesAsistentes models.
     * @return mixed
     */
    public function actionIndex($comite_id)
    {
        $searchModel = new ComitesAsistentesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$comite_id);
        
        $searchModel2 = new \app\models\UsuariosSearch();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams,1,$comite_id);
        $dataProvider2->pagination->pageSize=8;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'comite_id' => $comite_id,
            'searchModel2' =>$searchModel2,
            'dataProvider2' =>$dataProvider2,
        ]);
    }

    /**
     * Displays a single ComitesAsistentes model.
     * @param integer $comite_id
     * @param integer $usuario_id
     * @return mixed
     */
    public function actionView($comite_id, $usuario_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($comite_id, $usuario_id),
        ]);
    }

    /**
     * Creates a new ComitesAsistentes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ComitesAsistentes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'comite_id' => $model->comite_id, 'usuario_id' => $model->usuario_id]);
        } else {
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ComitesAsistentes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $comite_id
     * @param integer $usuario_id
     * @return mixed
     */
    public function actionUpdate($comite_id, $usuario_id, $submit = false)
    {
        $model = $this->findModel($comite_id, $usuario_id);

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
     * Deletes an existing ComitesAsistentes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $comite_id
     * @param integer $usuario_id
     * @return mixed
     */
    public function actionDelete($comite_id, $usuario_id)
    {
        $this->findModel($comite_id, $usuario_id)->delete();

        return $this->redirect(['index','comite_id'=>$comite_id]);
    }

    /**
     * Finds the ComitesAsistentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $comite_id
     * @param integer $usuario_id
     * @return ComitesAsistentes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($comite_id, $usuario_id)
    {
        if (($model = ComitesAsistentes::findOne(['comite_id' => $comite_id, 'usuario_id' => $usuario_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*
     * Crear Una *|Consulta|*
     */
    public function actionListausuarios($comite_id)
    {
        $searchModel2 = new \app\models\UsuariosSearch();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
          
        return $this->renderAjax('listausuarios',[
            'searchModel2' =>$searchModel2,
            'dataProvider2' =>$dataProvider2,
        ]);
        
    }
    
    public function actionRespuesta($id, $k){
       //CAMBIOS (*-*)\_
        $model = new ComitesAsistentes();
        
        if (Yii::$app->request->isAjax){
        
            $model->insertarUsuariosComite($id, $k);

            //$model->refresh();
            //Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->redirect(['index','comite_id'=>$id]);
        } 
    }
}
