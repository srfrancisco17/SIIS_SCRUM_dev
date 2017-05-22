<?php

namespace app\controllers;

use Yii;
use app\models\SprintUsuarios;
use app\models\SprintUsuariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
    
    public function actionKanban(){
        return $this->render('kanban');
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
