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
use app\models\Sprints;
use app\models\SprintUsuarios;

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
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    
    public function actionLogin(){
         
        if (!Yii::$app->user->isGuest) {

            $tipo_usuario = Yii::$app->user->identity->tipo_usuario;

            switch ($tipo_usuario) {

                case Usuarios::USUARIO_SCRUM_MASTER:

                    return $this->redirect(["site/index-scrum-master"]);

                case Usuarios::USUARIO_DEVELOPER:

                    return $this->redirect(["sprint-usuarios/index"]); 

            }

        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $tipo_usuario = Yii::$app->user->identity->tipo_usuario;

            switch ($tipo_usuario) {

                case Usuarios::USUARIO_SCRUM_MASTER:

                    return $this->redirect(["site/index-scrum-master"]);

                case Usuarios::USUARIO_DEVELOPER:

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
 
    
    public function actionIndexScrumMaster()
    {
        
        //$sprint_id = 1;
        
        $array_sprints = Sprints::find()->orderBy(['sprint_id'=>SORT_ASC])->asArray()->all();
        

 
        if (is_null(Yii::$app->request->post('sprint_id'))){
            
            $last_position = end($array_sprints);
            $sprint_id = $last_position['sprint_id'];
            
        }else{
            $sprint_id = Yii::$app->request->post('sprint_id');
        }
        
        
        foreach ($array_sprints as $index => $value){
            if($value['sprint_id'] == $sprint_id){
                
               //$ubicacion = $index;
               $array_actual = $value;
               break;
               
            }
        }
        
        $array_sprints_usuarios = SprintUsuarios::find()->where(['sprint_id'=>$sprint_id])->andWhere(['estado'=>'1'])->all();

        $whereUsuario = "";
        $usuario_id = Yii::$app->request->post('list');
        $titulo = '';
        $subtitulo = '---';
        
        if(empty($usuario_id)){
            
            // Diagrama De Todos Los Usuarios

            $consulta_tiempo_desarrollo = SprintRequerimientos::find()->joinWith('requerimiento')->where(['sprint_id' => $sprint_id])->sum('requerimientos.tiempo_desarrollo');
            $titulo = 'Total horas programas = '.$consulta_tiempo_desarrollo;
        
            
        }else{
            
            // Diagrama Por Usuario
            
            $consulta_tiempo_desarrollo = SprintRequerimientos::find()->joinWith('requerimiento')->where(['sprint_id' => $sprint_id])->andWhere(['usuario_asignado' => $usuario_id])->sum('requerimientos.tiempo_desarrollo');
            $whereUsuario= "and sr.usuario_asignado = ".$usuario_id." ";
            
            $titulo = 'Total horas asignadas = '.$consulta_tiempo_desarrollo;
           
        }
            
        $connection = Yii::$app->db;
        $consulta_acutal_burn = $connection->createCommand("select 
                                            sum(rt.horas_desarrollo) as sum_horas,
                                            rt.fecha_terminado::date
                                            from sprint_requerimientos as sr
                                            inner join sprint_requerimientos_tareas as srt
                                            on (
                                                    srt.requerimiento_id = sr.requerimiento_id
                                                    and srt.sprint_id = sr.sprint_id
                                            )
                                            inner join requerimientos_tareas as rt
                                            on (
                                                rt.requerimiento_id = srt.requerimiento_id
                                                and rt.tarea_id = srt.tarea_id
                                            )
                                            where sr.sprint_id = :sprint_id
                                            $whereUsuario
                                            and srt.estado = '4'
                                            group by rt.fecha_terminado::date
                                            order by rt.fecha_terminado::date")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->queryAll(); 
        
        
        //$subtitulo = $consulta_ideal_burn->sprint->sprint_alias.' | ('.$consulta_ideal_burn->sprint->fecha_desde.') - ('.$consulta_ideal_burn->sprint->fecha_hasta.')';
        
        $subtitulo = $array_actual['sprint_alias'].' | ('.$array_actual['fecha_desde'].') - ('.$array_actual['fecha_hasta'].')';

        $consulta_total_tareas = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                inner join sprint_requerimientos_tareas as srt
                on (
                    srt.requerimiento_id = sr.requerimiento_id
                    and srt.sprint_id = sr.sprint_id
                    )
                where sr.sprint_id = :sprint_id
                $whereUsuario
        ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar(); 
        
        $consulta_total_tareas_terminadas = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                inner join sprint_requerimientos_tareas as srt
                on (
                    srt.requerimiento_id = sr.requerimiento_id
                    and srt.sprint_id = sr.sprint_id
                    )
                where sr.sprint_id = :sprint_id
                $whereUsuario
                and srt.estado = '4' ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();        
        
        
        
        $consulta_total_requerimientos = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                where sprint_id = :sprint_id
                $whereUsuario   
        ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();
  
        
        $consulta_total_requerimientos_terminados = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                where sprint_id = :sprint_id
                $whereUsuario
                and estado = '4' 
        ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();   
        
        if ($consulta_total_tareas != 0){
            $porcentaje_tareas =  number_format($consulta_total_tareas_terminadas*100/$consulta_total_tareas, 2);
        }else{
            $porcentaje_tareas = 0;
        }
        
        $porcentaje_requerimientos =  number_format($consulta_total_requerimientos_terminados*100/$consulta_total_requerimientos, 2); 
        
        //Porcentaje Span Requerimientos
        
        $html_span_requerimientos = "";
        
        if($porcentaje_requerimientos == 0){
            $html_span_requerimientos = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> '.$porcentaje_requerimientos.'%</span>'; 
        }
        else if ($porcentaje_requerimientos <= 30){

            $html_span_requerimientos = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.$porcentaje_requerimientos.'%</span>'; 
        
            
        }else if ($porcentaje_requerimientos > 30) {
            $html_span_requerimientos = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.$porcentaje_requerimientos.'%</span>'; 
        }
        
        
        //Porcentaje Span tareas
        
        $html_span_tareas = "";
        
        if($porcentaje_tareas == 0){
            $html_span_tareas = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> '.$porcentaje_tareas.'%</span>'; 
        }
        else if ($porcentaje_tareas <= 30){

            $html_span_tareas = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.$porcentaje_tareas.'%</span>'; 
            
        }else if ($porcentaje_tareas > 30) {
            $html_span_tareas = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.$porcentaje_tareas.'%</span>'; 
        }

        $barChart = $connection->createCommand(" 

            select
                    usu.usuario_id, usu.nombres, usu.apellidos, sum(r.tiempo_desarrollo) as tiempo_total,
                    (
                            select
                                    sum(rt1.horas_desarrollo)
                            from sprint_requerimientos as sr1
                            left join sprint_requerimientos_tareas srt1
                            on(
                                    sr1.sprint_id = srt1.sprint_id
                                    and
                                    sr1.requerimiento_id = srt1.requerimiento_id
                            )
                            left join requerimientos_tareas as rt1
                            on(
                                    srt1.requerimiento_id = rt1.requerimiento_id
                                    and rt1.tarea_id = srt1.tarea_id
                            )
                            where sr1.sprint_id = 2 and srt1.estado = '4' and sr1.usuario_asignado = usu.usuario_id
                            group by sr1.usuario_asignado
                    ) as tiempo_terminado
            from
                    sprint_requerimientos as sr
            inner join requerimientos as r
            on (
                    r.requerimiento_id = sr.requerimiento_id
            )
            inner join usuarios as usu
            on (
                    usu.usuario_id = sr.usuario_asignado
            )
            where
                    sr.sprint_id = $sprint_id
            group by 1, 2, 3, sr.usuario_asignado
            having 
                    sum(r.tiempo_desarrollo) > 0
            order by usu.usuario_id desc
        
        ")->queryAll();        
        
        return $this->render('indexScrumMaster', [
            'array_actual' => $array_actual,
            'consulta_tiempo_desarrollo' => $consulta_tiempo_desarrollo,
            'consulta_acutal_burn' => $consulta_acutal_burn,
            'titulo' => $titulo,
            'subtitulo' => $subtitulo,
            'consulta_total_requerimientos' => $consulta_total_requerimientos,
            'consulta_total_requerimientos_terminados' => $consulta_total_requerimientos_terminados,
            'consulta_total_tareas' => $consulta_total_tareas,
            'consulta_total_tareas_terminadas' => $consulta_total_tareas_terminadas,
            'porcentaje_requerimientos' => $porcentaje_requerimientos,
            'html_span_requerimientos' => $html_span_requerimientos,
            'html_span_tareas' => $html_span_tareas,
            'array_sprints' => $array_sprints,
            'array_sprints_usuarios' => $array_sprints_usuarios,
            'barChart' => $barChart,
        ]);
    }    
    
    
    
    
    public function actionIndexDeveloper()
    {
        
        
        $array_sprints = Sprints::find()->orderBy(['sprint_id'=>SORT_ASC])->asArray()->all();
        $usuario_id = Yii::$app->user->identity->usuario_id;
        //$sprint_id = 2;
        
        
        if (is_null(Yii::$app->request->post('sprint_id'))){
            
            $last_position = end($array_sprints);
            $sprint_id = $last_position['sprint_id'];
            
        }else{
            $sprint_id = Yii::$app->request->post('sprint_id');
        }        
        
        $consulta_ideal_burn = SprintRequerimientos::findOne(['sprint_id' => $sprint_id, 'usuario_asignado' => $usuario_id]);
        $consulta_tiempo_desarrollo = SprintRequerimientos::find()->joinWith('requerimiento')->where(['sprint_id' => $sprint_id])->andWhere(['usuario_asignado' => $usuario_id])->sum('requerimientos.tiempo_desarrollo');
    
        $connection = Yii::$app->db;
        $consulta_acutal_burn = $connection->createCommand("select 
                                            sum(rt.horas_desarrollo) as sum_horas,
                                            rt.fecha_terminado::date
                                            from sprint_requerimientos as sr
                                            inner join sprint_requerimientos_tareas as srt
                                            on (
                                                    srt.requerimiento_id = sr.requerimiento_id
                                                    and srt.sprint_id = sr.sprint_id
                                            )
                                            inner join requerimientos_tareas as rt
                                            on (
                                                rt.requerimiento_id = srt.requerimiento_id
                                                and rt.tarea_id = srt.tarea_id
                                            )
                                            where sr.sprint_id = :sprint_id
                                            and sr.usuario_asignado = :usuario_asignado
                                            and srt.estado = '4'

                                            group by rt.fecha_terminado::date
                                            order by rt.fecha_terminado::date")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->bindValue(':usuario_asignado', $usuario_id)
                                            ->queryAll();        
         
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
                                                    ->bindValue(':sprint_id', $sprint_id)
                                                    ->bindValue(':usuario_asignado', $usuario_id)
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
                                                    ->bindValue(':sprint_id', $sprint_id)
                                                    ->bindValue(':usuario_asignado', $usuario_id)
                                                    ->queryScalar(); 

        $consulta_total_requerimientos = $connection->createCommand("select 
                                                                    COUNT(*)
                                                                    from sprint_requerimientos as sr
                                                                    where sprint_id = :sprint_id
                                                                    and usuario_asignado = :usuario_asignado")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->bindValue(':usuario_asignado', $usuario_id)
                                            ->queryScalar(); 
                  
        $consulta_total_requerimientos_terminados = $connection->createCommand("select 
                                                                    COUNT(*)
                                                                    from sprint_requerimientos as sr
                                                                    where sprint_id = :sprint_id
                                                                    and usuario_asignado = :usuario_asignado
                                                                    and estado = '4' ")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->bindValue(':usuario_asignado', $usuario_id)
                                            ->queryScalar(); 
        
        
        $barChart = $connection->createCommand(" 

            select
                    usu.usuario_id, usu.nombres, usu.apellidos, sum(r.tiempo_desarrollo) as tiempo_total,
                    (
                            select
                                    sum(rt1.horas_desarrollo)
                            from sprint_requerimientos as sr1
                            left join sprint_requerimientos_tareas srt1
                            on(
                                    sr1.sprint_id = srt1.sprint_id
                                    and
                                    sr1.requerimiento_id = srt1.requerimiento_id
                            )
                            left join requerimientos_tareas as rt1
                            on(
                                    srt1.requerimiento_id = rt1.requerimiento_id
                                    and rt1.tarea_id = srt1.tarea_id
                            )
                            where sr1.sprint_id = ".$sprint_id." and srt1.estado = '4' and sr1.usuario_asignado = ".$usuario_id."
                            
                    ) as tiempo_terminado
            from
                    sprint_requerimientos as sr
            inner join requerimientos as r
            on (
                    r.requerimiento_id = sr.requerimiento_id
            )
            inner join usuarios as usu
            on (
                    usu.usuario_id = sr.usuario_asignado
            )
            where
                    sr.sprint_id = ".$sprint_id." and usuario_asignado = ".$usuario_id."
            group by 1, 2, 3, sr.usuario_asignado            

        ")->queryOne();
        
        return $this->render('indexDeveloper',[
            'consulta_ideal_burn' => $consulta_ideal_burn,
            'consulta_tiempo_desarrollo' => $consulta_tiempo_desarrollo,
            'consulta_acutal_burn' => $consulta_acutal_burn,
            'consulta_total_requerimientos' => $consulta_total_requerimientos,
            'consulta_total_requerimientos_terminados' => $consulta_total_requerimientos_terminados,
            'consulta_total_tareas' => $consulta_total_tareas,
            'consulta_total_tareas_terminadas' => $consulta_total_tareas_terminadas,
            'array_sprints' => $array_sprints,
            'barChart' => $barChart,
        ]);
    }
}
