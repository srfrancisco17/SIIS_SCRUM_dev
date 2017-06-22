<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SprintRequerimientos;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Usuarios;
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index-scrum-master', 'index-developer','login','logout','contact'],
                'rules' => [
                    [
                        'actions' => ['logout','contact', 'index-developer'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index-scrum-master', 'about','logout'],
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    public function actionIndexScrumMaster()
    {
        return $this->render('indexScrumMaster');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    
    public function actionLogin(){
         
    if (!\Yii::$app->user->isGuest) {
   
        if (Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER){
            return $this->redirect(["site/index-scrum-master"]);
        }else if(Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_DEVELOPER){
            //return $this->redirect(["site/index-developer"]);
            return $this->redirect(["sprint-usuarios/index"]); 
        }
    }

    $model = new LoginForm();
        
    if ($model->load(Yii::$app->request->post()) && $model->login()) {

        if (Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER){
            return $this->redirect(["site/index-scrum-master"]);
        }else if(Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_DEVELOPER){
            //return $this->redirect(["site/index-developer"]);
            return $this->redirect(["sprint-usuarios/index"]); 
        }
    }else {
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        //return $this->goHome();
        return $this->redirect(['site/login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionIndexDeveloper()
    {
        
        $consulta_ideal_burn = SprintRequerimientos::findOne(['sprint_id' => '1', 'usuario_asignado' => Yii::$app->user->identity->usuario_id]);
        $consulta_tiempo_desarrollo = SprintRequerimientos::find()->where(['sprint_id' => '1'])->andWhere(['usuario_asignado' => Yii::$app->user->identity->usuario_id])->sum('tiempo_desarrollo');
    
        $connection = Yii::$app->db;
        $consulta_acutal_burn = $connection->createCommand("select 
                                            sum(srt.tiempo_desarrollo) as sum_horas,
                                            srt.fecha_terminado::date
                                            from sprint_requerimientos as sr
                                            inner join sprint_requerimientos_tareas as srt
                                            on (
                                                    srt.requerimiento_id = sr.requerimiento_id
                                                    and srt.sprint_id = sr.sprint_id
                                            )
                                            where sr.sprint_id = :sprint_id
                                            and sr.usuario_asignado = :usuario_asignado
                                            and srt.estado = '4'
                                            group by srt.fecha_terminado::date
                                            order by srt.fecha_terminado::date")
                                            ->bindValue(':sprint_id', 1)
                                            ->bindValue(':usuario_asignado', Yii::$app->user->identity->usuario_id)
                                            ->queryAll();        
        
        $consulta_total_requerimientos = SprintRequerimientos::find()
                                         ->select('usuario_asignado')
                                         ->where(['sprint_id' => '1'])
                                         ->andWhere(['usuario_asignado' => Yii::$app->user->identity->usuario_id])
                                         ->count();
        
        $consulta_total_requerimientos_terminados = SprintRequerimientos::find()
                                 ->select('usuario_asignado')
                                 ->where(['sprint_id' => '1'])
                                 ->andWhere(['usuario_asignado' => Yii::$app->user->identity->usuario_id])
                                 ->andWhere(['estado' => '4'])
                                 ->count();
        
        $consulta_total_tareas = $connection->createCommand("select 
                                                            COUNT(*)
                                                            from sprint_requerimientos as sr
                                                            inner join sprint_requerimientos_tareas as srt
                                                            on (
                                                                srt.requerimiento_id = sr.requerimiento_id
                                                                and srt.sprint_id = sr.sprint_id
                                                            )
                                                            where sr.sprint_id = :sprint_id
                                                            and sr.usuario_asignado = :usuario_asignado")
                                                    ->bindValue(':sprint_id', 1)
                                                    ->bindValue(':usuario_asignado', Yii::$app->user->identity->usuario_id)
                                                    ->queryScalar(); 
        
        $consulta_total_tareas_terminadas = $connection->createCommand("select 
                                                            COUNT(*)
                                                            from sprint_requerimientos as sr
                                                            inner join sprint_requerimientos_tareas as srt
                                                            on (
                                                                srt.requerimiento_id = sr.requerimiento_id
                                                                and srt.sprint_id = sr.sprint_id
                                                            )
                                                            where sr.sprint_id = :sprint_id
                                                            and sr.usuario_asignado = :usuario_asignado
                                                            and srt.estado = '4' ")
                                                    ->bindValue(':sprint_id', 1)
                                                    ->bindValue(':usuario_asignado', Yii::$app->user->identity->usuario_id)
                                                    ->queryScalar();         
        
        
        //return $this->render('indexDeveloper');
        
        return $this->render('indexDeveloper',[
            'consulta_ideal_burn'=>$consulta_ideal_burn,
            'consulta_tiempo_desarrollo' => $consulta_tiempo_desarrollo,
            'consulta_acutal_burn' => $consulta_acutal_burn,
            'consulta_total_requerimientos' => $consulta_total_requerimientos,
            'consulta_total_requerimientos_terminados' => $consulta_total_requerimientos_terminados,
            'consulta_total_tareas' => $consulta_total_tareas,
            'consulta_total_tareas_terminadas' => $consulta_total_tareas_terminadas,
        ]);
    }
}
