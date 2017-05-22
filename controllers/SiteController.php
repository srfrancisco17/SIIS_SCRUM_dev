<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;

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
                'only' => ['index-scrum-master','login','logout','contact','about','index'],
                'rules' => [
                    [
                        'actions' => ['logout','contact'],
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
    
    /*
    public function actionIndex()
    {
        return $this->render('index');
    }
    */
    
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
            return $this->redirect(["site/index-developer"]);
        }
    }

    $model = new LoginForm();
        
    if ($model->load(Yii::$app->request->post()) && $model->login()) {

        if (Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER){
            return $this->redirect(["site/index-scrum-master"]);
        }else if(Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_DEVELOPER){
            return $this->redirect(["site/index-developer"]);
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
        return $this->render('indexDeveloper');
    }
}
